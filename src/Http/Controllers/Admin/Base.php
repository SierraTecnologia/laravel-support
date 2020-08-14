<?php

namespace Support\Http\Controllers\Admin;

use App;
use URL;
use View;
use Support;
use SupportURL;
use Event;
use Former;
use Request;
use Redirect;
use Response;
use stdClass;
use Validator;
use Illuminate\Support\Str;
use Support\Template\Input\Search;
use Bkwld\Library\Utils\File;
use Support\Template\Input\Sidebar;
use Support\Elements\Fields\Listing;
use Translation\Template\Localize;
use Support\Template\Input\Position;
use Illuminate\Routing\Controller;
use Support\Template\Input\NestedModels;
use Support\Template\Input\ModelValidator;
use Support\Models\Base as BaseModel;
use Support\Exceptions\ValidationFail;
use Bkwld\Library\Laravel\Validator as BkwldLibraryValidator;

use Support\Http\Controllers\Controller as BaseController;

/**
 * The base controller is gives Decoy most of the magic/for-free mojo
 * It's not abstract because it can't be instantiated with PHPUnit like that
 */
class Base extends BaseController
{
    use \Muleta\Traits\Controllers\Exportable;

    //---------------------------------------------------------------------------
    // Default settings
    //---------------------------------------------------------------------------

    /**
     * Amount of results to return per page
     *
     * @var integer
     */
    public static $per_page = 20;

    /**
     * Amount of results to show in the sidebar layout
     *
     * @var integer
     */
    public static $per_sidebar = 6;

    /**
     * Include soft deleted models in the listing
     *
     * @var boolean
     */
    protected $with_trashed = false;

    /**
     * The model class name that the contorller manages. Ex: Post
     *
     * @var string
     */
    protected $model;

    /**
     * The columns to show in the listing view.  The keys are the labels in the
     * table header.  The value is where to get the content for the cell.  Like a
     * database column name or an method name on the model.
     *
     * @var array
     */
    protected $columns = ['Title' => 'getAdminTitleHtmlAttribute'];

    /**
     * The view-style path to the edit view.  Ex: admin.news.edit
     *
     * @var string
     */
    protected $show_view;

    /**
     * The search configuration.  See the docs for more info
     *
     * @var array
     */
    protected $search;

    //---------------------------------------------------------------------------
    // Properties that define relationships
    //---------------------------------------------------------------------------

    /**
     * An instance of the model that is the parent of the controller that is handling
     * the request
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $parent;

    /**
     * Model class name i.e. Photo
     *
     * @var string
     */
    protected $parent_model;

    /**
     * Controller class name i.e. Admin\PhotosController
     *
     * @var string
     */
    protected $parent_controller;

    /**
     * Relationship name on parents i.e. photos
     *
     * @var string
     */
    protected $parent_to_self;

    /**
     * Relationship name on this controller's model to the parent i.e. post
     *
     * @var string
     */
    protected $self_to_parent;

    /**
     * Populate protected properties on init
     */
    public function __construct()
    {
        parent::__construct();

        // Figure out what the show view should be.  This is the path to the show
        // view file.  Such as 'admin.news.edit'
        if (empty($this->show_view)) {
            $this->show_view = $this->detailPath($this->controller);
        }

        // Try to suss out the model by singularizing the controller
        if (empty($this->model)) {
            $this->model = $this->model($this->controller);
            if (!class_exists($this->model)) {
                $this->model = null;
            }
        }

        // If the input contains info on the parent, immediately instantiate
        // the parent instance.  These are populated by some AJAX calls like
        // autocomplete on a many to many and the attach method.
        if (($parent_id = request('parent_id'))
            && ($parent_controller = request('parent_controller'))
        ) {
            $parent_model_class = $this->model($parent_controller);
            $this->parent($parent_model_class::findOrFail($parent_id));
        }
    }

    //---------------------------------------------------------------------------
    // Getter/setter
    //---------------------------------------------------------------------------


    /**
     * Get the columns for a controller
     *
     * @return array
     */
    public function columns()
    {
        return $this->columns;
    }

    /**
     * Get the search settings for a controller, merging in default selectors
     *
     * @return array
     */
    public function search()
    {
        $search = new Search;
        return array_merge(
            $search->makeSoftDeletesCondition($this),
            $this->search ?: []
        );
    }

    /**
     * Get the with_trashed settings for a controller
     *
     * @return array
     */
    public function withTrashed()
    {
        return $this->with_trashed;
    }

    /**
     * Get the directory for the detail views.  It's based off the controller name.
     * This is basically a conversion to snake case from studyly case
     *
     * @param  string $class ex: 'Admin\NewsController'
     * @return string ex: admin.edit or car_lovers.edit
     */
    public function detailPath($class)
    {
        // Remove Decoy from the class
        $path = str_replace('Support\Http\Controllers\Admin\\', '', $class, $is_facilitador);

        // Remove the App controller prefix
        $path = str_replace('App\Http\Controllers\\', '', $path);

        // Break up all the remainder of the class and de-study them (which is what
        // title() does)
        $parts = explode('\\', $path);

        foreach ($parts as &$part) {
            $part = str_replace(' ', '_', strtolower($this->title($part)));
        }

        $path = implode('.', $parts);

        // If the controller is part of Decoy, add it to the path
        if ($is_facilitador) {
            $path = 'facilitador::'.$path;
        }

        // Done
        return $path.'.edit';
    }

    /**
     * Figure out the model for a controller class or return the current model class
     *
     * @param  string $class ex: "Admin\SlidesController"
     * @return string ex: "Slide"
     */
    public function model($class = null)
    {
        if ($class) {
            return Support::modelForController($class);
        }

        return $this->model;
    }

    /**
     * Give this controller a parent model instance.  For instance, this makes the
     * index view a listing of just the children of the parent.
     *
     * @param  Illuminate\Database\Eloquent\Model $parent
     * @return $this
     */
    public function parent($parent)
    {
        // Save out the passed reference
        $this->parent = $parent;

        // Save out sub properties that I hope to deprecate
        $this->parent_model = get_class($this->parent);
        $this->parent_controller = Support::controllerForModel($this->parent_model);

        // Figure out what the relationship function to the child (this controller's
        // model) on the parent model .  It will be the plural version of this
        // model's name.
        $this->parent_to_self = Support::hasManyName($this->model);

        // If the parent is the same as this controller, assume that it's a
        // many-to-many-to-self relationship.  Thus, expect a relationship method to
        // be defined on the model called "RELATIONSHIPAsChild".  I.e. "postsAsChild"
        if ($this->parent_controller == $this->controller && method_exists($this->model, $this->parent_to_self.'AsChild')) {
            $this->self_to_parent = $this->parent_to_self.'AsChild';

            // If the parent relationship is a polymorphic one-many, then the
            // relationship function on the child model will be the model name plus
            // "able".  For instance, the Link model would have it's relationship to
            // parent called "linkable".
        } elseif (is_a($this->parentRelation(), 'Illuminate\Database\Eloquent\Relations\MorphMany')) {
            $this->self_to_parent = Support::belongsToName($this->model).'able';

            // Save out to self to parent relationship.  It will be singular if the
            // relationship is a many to many.
        } else {
            $this->self_to_parent = $this->isChildInManyToMany()?
                Support::hasManyName($this->parent_model):
                Support::belongsToName($this->parent_model);
        }

        // Make chainable
        return $this;
    }

    /**
     * Determine whether the relationship between the parent to this controller
     * is a many to many
     *
     * @return boolean
     */
    public function isChildInManyToMany()
    {
        return is_a(
            $this->parentRelation(),
            'Illuminate\Database\Eloquent\Relations\BelongsToMany'
        );
    }

    /**
     * Get the permission options for the controller.  By default, these are the
     * stanadard CRUD actions
     *
     * @return array An associative array.  The keys are the permissions slugs.
     *               The value is either the description as a string or an array
     *               with the first index being an english title and the second
     *               being the description.
     */
    public function getPermissionOptions()
    {
        return [
            'read'    => 'View listing and edit views',
            'create'  => 'Create new items',
            'update'  => 'Update existing items',
            'publish' => 'Move from "draft" to "published"',
            'destroy' => ['Delete', 'Delete items permanently'],
        ];
    }

    //---------------------------------------------------------------------------
    // Basic CRUD methods
    //---------------------------------------------------------------------------

    /**
     * Show an index, listing page.  Sets view via the layout.
     *
     * @return Illuminate\Contracts\View\Factory
     */
    public function index()
    {
        // Look for overriden views
        $this->overrideViews();

        // Get models to show
        $results = $this->makeIndexQuery()->paginate($this->perPage());

        // Render the view using the `listing` builder
        $listing = Listing::createFromController($this, $results);
        if ($this->parent) {
            $listing->parent($this->parent);

            // The layout header may have a many to many autocomplete
            $this->layout->with($this->autocompleteViewVars());
        }

        // Render view
        return $this->populateView($listing);
    }

    /**
     * Show the create form.  Sets view via the layout.
     *
     * @return Illuminate\Contracts\View\Factory
     */
    public function create()
    {
        // Look for overriden views
        $this->overrideViews();

        // Pass validation through
        Former::withRules($this->getRules());

        // Initialize localization
        with($localize = new Localize)
            ->model($this->model)
            ->title(Str::singular($this->title));

        // Make the sidebar
        $sidebar = new Sidebar;
        if (!$localize->hidden()) {
            $sidebar->addToEnd($localize);
        }

        // Render view
        return $this->populateView(
            $this->show_view, [
            'item' => null,
            'localize' => $localize,
            'sidebar' => $sidebar,
            'parent_id' => $this->parent ? $this->parent->getKey() : null,
            ]
        );
    }

    /**
     * Store a new record
     *
     * @return Symfony\Component\HttpFoundation\Response Redirect to edit view
     */
    public function store()
    {
        // Create a new object
        $item = new $this->model;

        // Remove nested model data from input and prepare to insert on save
        $input = (new NestedModels)->relateTo($item);

        // Hydrate the object
        $item->fill($input);

        // Validate and save.
        $this->validateEloquentData($item);
        if ($this->parent) {
            $this->parent->{$this->parent_to_self}()->save($item);
        } else {
            $item->save();
        }

        // Redirect to edit view
        if (Request::ajax()) {
            return Response::json(['id' => $item->id]);
        } else {
            return Redirect::to(SupportURL::relative('edit', $item->id))
            ->with('success', $this->successMessage($item, 'created'));
        }
    }

    /**
     * Show the edit form.  Sets view via the layout.
     *
     * @param  int $id Model key
     * @return Illuminate\Contracts\View\Factory
     */
    public function edit($id)
    {
        // Get the model instance
        $item = $this->findOrFail($id);

        // Respond to AJAX requests for a single item with JSON
        if (Request::ajax()) {
            return Response::json($item);
        }

        // Look for overriden views
        $this->overrideViews();

        // Populate form
        Former::populate($item);
        Former::withRules($this->getRules());

        // Initialize localization
        with($localize = new Localize)
            ->item($item)
            ->title(Str::singular($this->title));

        // Make the sidebar
        $sidebar = new Sidebar($item);
        if (!$localize->hidden()) {
            $sidebar->addToEnd($localize);
        }

        // Render view
        return $this->populateView(
            $this->show_view, [
            'item' => $item,
            'localize' => $localize,
            'sidebar' => $sidebar,
            'parent_id' => $this->parent ? $this->parent->getKey() : null,
            ]
        );
    }

    /**
     * Update a record
     *
     * @param  int $id Model key
     * @return Symfony\Component\HttpFoundation\Response Redirect to edit view
     */
    public function update($id)
    {
        // Get the model instance
        $item = $this->findOrFail($id);

        // Remove nested model data from input and prepare to insert on save
        $input = (new NestedModels)->relateTo($item);

        // Hydrate for drag-and-drop sorting
        if (Request::ajax()
            && ($position = new Position($item, $this->self_to_parent))
            && $position->has()
        ) {
            $position->fill();
        }

        // ... else hydrate normally
        else {
            $item->fill($input);
            if (isset($item::$rules['slug'])) {
                $pattern = '#(unique:\w+)(,slug)?(,(NULL|\d+))?#';
                $item::$rules['slug'] = preg_replace($pattern, '$1,slug,'.$id, $item::$rules['slug']);
            }
        }

        // Save the record
        $this->validateEloquentData($item);
        $item->save();

        // Redirect to the edit view
        if (Request::ajax()) {
            return Response::json();
        } else {
            return Redirect::to(URL::current())
                ->with('success', $this->successMessage($item));
        }
    }

    /**
     * Destroy a record
     *
     * @param  int $id Model key
     * @return Symfony\Component\HttpFoundation\Response Redirect to listing
     */
    public function destroy($id)
    {
        // Find the item
        $item = $this->findOrFail($id);

        // Delete row (this should trigger file attachment deletes as well)
        $item->delete();

        // As long as not an ajax request, go back to the parent directory of the referrer
        if (Request::ajax()) {
            return Response::json();
        } else {
            return Redirect::to(SupportURL::relative('index'))
                ->with('success', $this->successMessage($item, 'deleted'));
        }
    }

    //---------------------------------------------------------------------------
    // Special actions
    //---------------------------------------------------------------------------

    /**
     * Duplicate a record
     *
     * @param  int $id Model key
     * @return Symfony\Component\HttpFoundation\Response Redirect to new record
     */
    public function duplicate($id)
    {
        // Find the source item
        $src = $this->findOrFail($id);
        if (empty($src->cloneable)) {
            return App::abort(404);
        }

        // Duplicate using Bkwld\Cloner
        $new = $src->duplicate();

        // Don't make duplicates public
        if (array_key_exists('public', $new->getAttributes())) {
            $this->public = 0;
        }

        // If there is a name or title field, append " copy" to it.  Use "original"
        // to avoid mutators.
        if ($name = $new->getOriginal('name')) {
            $new->setAttribute('name', $name.' copy');
        } elseif ($title = $new->getOriginal('title')) {
            $new->setAttribute('title', $title.' copy');
        }

        // Set localization options on new instance
        if ($locale = request('locale')) {
            $new->locale = $locale;
            if (isset($src->locale_group)) {
                $new->locale_group = $src->locale_group;
            }
        }

        // Save any changes that were made
        $new->save();

        // Save the new record and redirect to its edit view
        return Redirect::to(SupportURL::relative('edit', $new->getKey()))
            ->with('success', $this->successMessage($src, 'duplicated'));
    }

    //---------------------------------------------------------------------------
    // Many To Many CRUD
    //---------------------------------------------------------------------------

    /**
     * List as JSON for autocomplete widgets
     *
     * @return Illuminate\Http\Response JSON
     */
    public function autocomplete()
    {
        // Do nothing if the query is too short
        if (strlen(request('query')) < 1) {
            return Response::json();
        }

        // Get an instance so the title attributes can be found.  If none are found,
        // then there are no results, so bounce
        if (!$model = call_user_func([$this->model, 'first'])) {
            return Response::json($this->formatAutocompleteResponse([]));
        }

        // Get data matching the query
        $query = call_user_func([$this->model, 'titleContains'], request('query'))
            ->orderedForce()
            ->take(15); // Note, this is also enforced in the autocomplete.js

        // Don't return any rows already attached to the parent.  So make sure the
        // id is not already in the pivot table for the parent
        if ($this->isChildInManyToMany()) {

            // See if there is an exact match on what's been entered.  This is useful
            // for many to manys with tags because we want to know if the reason that
            // autocomplete returns no results on an exact match that is already
            // attached is because it already exists.  Otherwise, it would allow the
            // user to create the tag
            if ($this->parentRelation()->titleContains(request('query'), true)->count()
            ) {
                return Response::json(['exists' => true]);
            }

            // Get the ids of already attached rows through the relationship function.
            // There are ways to do just in SQL but then we lose the ability for the
            // relationship function to apply conditions, like is done in polymoprhic
            // relationships.
            $siblings = $this->parentRelation()->get();
            if (count($siblings)) {
                $sibling_ids = [];
                foreach ($siblings as $sibling) {
                    $sibling_ids[] = $sibling->id;
                }

                // Add condition to query
                $model = new $this->model;
                $query = $query->whereNotIn($model->getQualifiedKeyName(), $sibling_ids);
            }
        }

        // Return result
        return Response::json($this->formatAutocompleteResponse($query->get()));
    }

    /**
     * Return key-val pairs needed for view partials related to many to many
     * autocompletes
     *
     * @return array key-val pairs
     */
    public function autocompleteViewVars()
    {
        if (!$this->parent) {
            return [];
        }

        $parent_controller = new $this->parent_controller;

        return [
            'parent_id' => $this->parent->getKey(),
            'parent_controller' => $this->parent_controller,
            'parent_controller_title' => $parent_controller->title(),
            'parent_controller_description' => $parent_controller->description(),
            'many_to_many' => $this->isChildInManyToMany(),
        ];
    }

    /**
     * Attach a model to a parent_id, like with a many to many style
     * autocomplete widget
     *
     * @param  int $id The id of the parent model
     * @return Illuminate\Http\Response JSON
     */
    public function attach($id)
    {
        // Require there to be a parent id and a valid id for the resource
        $item = $this->findOrFail($id);

        // Do the attach
        $item->fireDecoyEvent('attaching', [$item, $this->parent]);
        $item->{$this->self_to_parent}()->attach($this->parent);
        $item->fireDecoyEvent('attached', [$item, $this->parent]);

        // Return the response
        return Response::json();
    }

    /**
     * Remove a relationship.  Very similar to delete, except that we're
     * not actually deleting from the database
     *
     * @param  mixed $id One or many (commaa delimited) parent ids
     * @return Illuminate\Http\Response Either a JSON or Redirect response
     */
    public function remove($id)
    {
        // Support removing many ids at once
        $ids = Request::has('ids') ? explode(',', request('ids')) : [$id];

        // Get the model instances for each id, for the purpose of event firing
        $items = array_map(
            function ($id) {
                return $this->findOrFail($id);
            }, $ids
        );

        // Lookup up the parent model so we can bulk remove multiple of THIS model
        foreach ($items as $item) {
            $item->fireDecoyEvent('removing', [$item, $this->parent]);
        }
        $this->parentRelation()->detach($ids);
        foreach ($items as $item) {
            $item->fireDecoyEvent('removed', [$item, $this->parent]);
        }

        // Redirect.  We can use back cause this is never called from a "show"
        // page like get_delete is.
        if (Request::ajax()) {
            return Response::json();
        }

        return Redirect::back();
    }

    //---------------------------------------------------------------------------
    // Utility methods
    //---------------------------------------------------------------------------

    /**
     * Make the index query, including applying a search
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function makeIndexQuery()
    {
        // Open up the query. We can assume that Model has an ordered() function
        // because it's defined on Decoy's Base_Model
        $query = $this->parent ?
            $this->parentRelation()->orderedForce() :
            call_user_func([$this->model, 'orderedForce']);

        // Allow trashed records
        if ($this->withTrashed()) {
            $query->withTrashed();
        }

        // Apply search
        $search = new Search();
        $query = $search->apply($query, $this->search());
        return $query;
    }

    /**
     * Helper for getting a model instance by ID
     *
     * @param  scalar $id
     * @return Eloquent\Model
     */
    protected function findOrFail($id)
    {
        $model = $this->model;
        if ($this->withTrashed()) {
            return $model::withTrashed()->findOrFail($id);
        } else {
            return $model::findOrFail($id);
        }
    }

    /**
     * Get the rules for the model
     *
     * @return array
     */
    protected function getRules()
    {
        $class = $this->model; // PHP won't allow as a one-liner

        return $class::$rules;
    }

    /**
     * All actions validate in basically the same way.  This is shared logic for that
     *
     * @param  BaseModel|Request|array $data
     * @param  array                   $rules    A Laravel rules array. If null, will be pulled from model
     * @param  array                   $messages Special error messages
     * @return void
     *
     * @throws ValidationFail
     */
    public function validateEloquentData($data, $rules = null, $messages = [])
    {
        // A request may be passed in when using Laravel traits, like how resetting
        // passwords work.  Get the input from it
        if (is_a($data, \Illuminate\Http\Request::class)) {
            $data = $data->input();
        }

        // Get validation rules from model
        $model = null;
        if (is_a($data, BaseModel::class)) {
            $model = $data;
            $data = $model->getAttributes();
            if (empty($rules)) {
                $rules = $model::$rules;
            }
        }

        // If an AJAX update, don't require all fields to be present. Pass just the
        // keys of the input to the array_only function to filter the rules list.
        if (Request::ajax() && Request::getMethod() == 'PUT') {
            $rules = array_only($rules, array_keys(request()->input()));
        }

        // Stop if no rules
        if (empty($rules)) {
            return;
        }

        // Build the validation instance and fire the intiating event.
        if ($model) {
            (new ModelValidator)->validateEloquentData($model, $rules, $messages);
        } else {
            $messages = array_merge(BkwldLibraryValidator::$messages, $messages);
            $validation = Validator::make($data, $rules, $messages);
            if ($validation->fails()) {
                throw new ValidationFail($validation);
            }
        }
    }

    /**
     * Format the results of a query in the format needed for the autocomplete
     * responses
     *
     * @param  array $results
     * @return array
     */
    public function formatAutocompleteResponse($results)
    {
        $output = [];
        foreach ($results as $row) {

            // Only keep the id and title fields
            $item = new stdClass;
            $item->id = $row->getKey();
            $item->title = $row->getAdminTitleAttribute();

            // Add properties for the columns mentioned in the list view within the
            // 'columns' property of this row in the response.  Use the same logic
            // found in Support::renderListColumn();
            $item->columns = [];
            foreach ($this->columns() as $column) {
                if (method_exists($row, $column)) {
                    $item->columns[$column] = call_user_func([$row, $column]);
                } elseif (isset($row->$column)) {
                    if (is_a($row->$column, 'Carbon\Carbon')) {
                        $item->columns[$column] = $row->$column->format(FORMAT_DATE);
                    } else {
                        $item->columns[$column] = $row->$column;
                    }
                } else {
                    $item->columns[$column] = null;
                }
            }

            // Add the item to the output
            $output[] = $item;
        }

        return $output;
    }

    // Return the per_page based on the input
    public function perPage()
    {
        $per_page = request('count', static::$per_page);
        if ($per_page == 'all') {
            return 1000;
        }

        return $per_page;
    }

    /**
     * Run the parent relationship function for the active model, returning the Relation
     * object. Returns false if none found.
     *
     * @return Illuminate\Database\Eloquent\Relations\Relation | false
     */
    private function parentRelation()
    {
        if ($this->parent && method_exists($this->parent, $this->parent_to_self)) {
            return $this->parent->{$this->parent_to_self}();
        }

        return false;
    }

    /**
     * Tell Laravel to look for view files within the app admin views so that,
     * on a controller-level basis, the app can customize elements of an admin
     * view through it's partials.
     *
     * @return void
     */
    protected function overrideViews()
    {
        $dir = Str::snake($this->controllerName());
        $path = base_path('resources/views/admin/').$dir;
        app('view')->prependNamespace('facilitador', $path);
    }

    /**
     * Creates a success message for CRUD commands
     *
     * @param  Support\Model\Base|string $title The model instance that is
     *                                              being worked on  or a string
     *                                              containing the title
     * @param  string                        $verb  Default: 'saved'. Past tense CRUD verb (created, saved, etc)
     * @return string                        The CRUD success message string
     */
    protected function successMessage($input = '', $verb = 'saved')
    {
        // Figure out the title and wrap it in quotes
        $title = $input;
        if (is_a($input, '\Support\Models\Base')) {
            $title = $input->getAdminTitleAttribute();
        }

        if ($title && is_string($title)) {
            $title =  '"'.$title.'"';
        }

        // Render the message
        $message = __('facilitador::base.success_message', ['model' => Str::singular($this->title), 'title' => $title, 'verb' => __("facilitador::base.verb.$verb")]);

        // Add extra messaging for copies
        if ($verb == 'duplicated') {
            $url = preg_replace('#/duplicate#', '/edit', Request::url());
            $message .= __('facilitador::base.success_duplicated', ['url' => $url]);
        }

        // Add extra messaging if the creation was begun from the localize UI
        if ($verb == 'duplicated' && is_a($input, '\Support\Models\Base') && !empty($input->locale)) {
            $message .= __('facilitador::base.success_localized', ['locale' => \Illuminate\Support\Facades\Config::get('sitec.site.locales')[$input->locale]]);
        }

        // Return message
        return $message;
    }
}
