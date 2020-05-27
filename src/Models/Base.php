<?php

namespace Support\Models;

use DB;
use App;
use URL;
use Facilitador;
use Event;
use Config;
use Session;
use FacilitadorURL;
use Bkwld\Cloner\Cloneable;
use Bkwld\Upchuck\SupportsUploads;
use Bkwld\Library\Utils\Collection;
use Support\Exceptions\Exception;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Log;
use Doctrine\DBAL\Types\StringType as DoctrineStringType;
use Illuminate\Support\Str;
use Watson\Validating\ValidatingTrait;
use Support\Services\ModelService;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Extratores\DbalExtractor;
use Support\Utils\Mergeators\DbalMergeator;
use Support\Utils\Inclusores\DbalInclusor;

use Support\Traits\Models\Importable;

abstract class Base extends Eloquent
{
    /**
     * @todo bug Resolver pra tirar esse coment
     * [2020-02-02 08:18:39] local.ERROR: SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' (SQL: select count(*) as aggregate from `users` where `email` = rafacollares@hotmail.com and `2` <> 2) {"exception":"[object] (Illuminate\\Database\\QueryExcept
    * ion(code: 42S22): SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' (SQL: select count(*) as aggregate from `users` where `email` = rafacollares@hotmail.com and `2` <> 2) at /var/www/html/vendor/laravel/framework/src/Illuminate/Database/Connecti
    * on.php:664, Doctrine\\DBAL\\Driver\\PDOException(code: 42S22): SQLSTATE[42S22]: Column not found: 1054 Unknown column '2' in 'where clause' at /var/www/html/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:63, PDOException(code: 42S22): SQLSTATE[42S22]: Col
    * umn not found: 1054 Unknown column '2' in 'where clause' at /var/www/html/vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOConnection.php:61)                                                                                                                                   
    * [stacktrace]                                                                                                                                   
     */
    use /*ValidatingTrait, */SortableTrait, Importable;

    //---------------------------------------------------------------------------
    // Overrideable properties
    //---------------------------------------------------------------------------

    /**
     * This should be overridden by Models to store the array of their
     * Laravel validation rules
     *
     * @var array
     */
    // public $rules = [];
    public $rules = [
        
    ];
    
    /**
     * Should this model be localizable in the admin.  If not undefined, will
     * override the site config "auto_localize_root_models"
     *
     * @var boolean
     */
    public static $localizable;

    /**
     * If false, this model cannot be cloned
     *
     * @var boolean
     */
    public $cloneable = true;

    /**
     * Specify columns that shouldn't be duplicated by Bkwld\Cloner.  Include
     * slug by default so that Sluggable will automatically generate a new one.
     *
     * @var array
     */
    protected $clone_exempt_attributes = ['slug'];

    /**
     * Relations to follow when models are duplicated
     *
     * @var array
     */
    protected $cloneable_relations;

    /**
     * If populated, these will be used instead of the files that are found
     * automatically by getCloneableFileAttributes()
     *
     * @var array
     */
    protected $cloneable_file_attributes;

    /**
     * Should the model be exportable as CSV?
     *
     * @var boolean
     */
    public $exportable = false;

    /**
     * If populated, these will ignore the override mutators in admin that are
     * in hasGetMutator() and hasSetMutator()
     *
     * @var array
     */
    protected $admin_mutators = [];


    //---------------------------------------------------------------------------
    // Instantiation
    //---------------------------------------------------------------------------

    /**
     * Constructor registers events and configures mass assignment
     */
    public function __construct(array $attributes = [])
    {
        // Blacklist special columns that aren't intended for the DB
        $this->guarded = array_merge(
            $this->guarded, [
            'parent_controller', // Backbone.js sends this with sort updates
            'parent_id', // Backbone.js may also send this with sort
            'select-row', // This is the name of the checkboxes used for bulk delete
            ]
        );

        // Continue Laravel construction
        parent::__construct($attributes);
    }


    /**
     * @todo Fazer
     */
    public function getIdentificador()                                                                                                                                                          
    {                                                                                                                    
        return $this->{$this->getKeyName()};
    }

    /**
     * No-Op callbacks invoked by Observers\ModelCallbacks.  These allow quick handling
     * of model event states.
     *
     * @return void|false
     */
    public function onSaving()
    { 
    }
    public function onSaved()
    { 
    }
    public function onCreating()
    { 
    }
    public function onCreated()
    { 
    }
    public function onUpdating()
    { 
    }
    public function onUpdated()
    { 
    }
    public function onDeleting()
    { 
    }
    public function onDeleted()
    { 
    }

    /**
     * Validation callback no-ops
     *
     * @param  $validation Illuminate\Validation\Validator
     * @return void|false
     */
    public function onValidating($validation)
    { 
    }
    public function onValidated($validation)
    { 
    }

    /**
     * Many to many attach/detach callback no-ops
     *
     * @param  $parent Eloquent\Model
     * @return void|false
     */
    public function onAttaching($parent)
    { 
    }
    public function onAttached($parent)
    { 
    }
    public function onRemoving($parent)
    { 
    }
    public function onRemoved($parent)
    { 
    }

    /**
     * Check for a validation rule for a slug column
     *
     * @return boolean
     */
    protected function needsSlugging()
    {
        return array_key_exists('slug', $this->rules);
    }

    public function getApresentationName()
    {
        return $this->{$this->getApresentationNameKey()};
    }

    public function getApresentationNameKey()
    {
        $atributesInOrderToDisplay = [
            'name',
            'slug',
            'text',
            'token',
        ];
        $attributes = $this->getFillable();
        foreach ($atributesInOrderToDisplay as $display) {
            if (in_array($display, $attributes)) {
                return $display;
            }
        }

        return $this->getKeyName();
    }

    //---------------------------------------------------------------------------
    // Accessors
    //---------------------------------------------------------------------------

    /**
     * Return the title for the row for the purpose of displaying in admin list
     * views and breadcrumbs.  It looks for columns that are named like common
     * things that would be titles.
     *
     * @return string
     */
    public function getAdminTitleHtmlAttribute()
    {
        return $this->getAdminThumbTagAttribute().$this->getAdminTitleAttribute();
    }

    /**
     * Deduce the source for the title of the model and return that title
     *
     * @return string
     */
    public function getAdminTitleAttribute()
    {
        return implode(
            ' ', array_map(
                function ($attribute) {
                    return $this->$attribute;
                }, $this->titleAttributes()
            )
        ) ?: __('facilitador::base.untitled');
    }

    /**
     * Add a thumbnail img tag to the title
     *
     * @return string IMG tag
     */
    public function getAdminThumbTagAttribute()
    {
        if (!$url = $this->getAdminThumbAttribute()) {
            return;
        }

        return sprintf('<img src="%s" alt="">', $url);
    }

    /**
     * The URL for the thumbnail
     *
     * @return string URL
     */
    public function getAdminThumbAttribute($width = 40, $height = 40)
    {

        // Check if there are images for the model
        if (!method_exists($this, 'images')) {
            return;
        }

        $images = $this->images;
        if ($images->isEmpty()) {
            return;
        }

        // Get null-named (default) images first
        return $images->sortBy('name')->first()->crop($width, $height)->url;
    }

    /**
     * Get the URL sitemaps generated by Bkwld\SitemapFromRoutes
     *
     * @return string
     */
    public function getSitemapUrlAttribute()
    {
        return $this->uri;
    }

    /**
     * A no-op that should return the URI (an absolute path or a fulL URL) to the record
     *
     * @return string
     */
    public function getUriAttribute()
    {
    }

    /**
     * Get all file fields by looking at Upchuck config and validation rules
     *
     * @return array The keys of all the attributes that store file references
     */
    public function getFileAttributesAttribute()
    {

        // Get all the file validation rule keys
        $attributes = array_keys(
            array_filter(
                $this->rules, function ($rules) {
                    return preg_match('#file|image|mimes|video|dimensions#i', $rules);
                }
            )
        );

        // Get all the model attributes from upchuck
        if (method_exists($this, 'getUploadMap')) {
            $attributes = array_unique(
                array_merge(
                    $attributes,
                    array_values($this->getUploadMap())
                )
            );
        }

        // Return array of attributes
        return $attributes;
    }

    /**
     * Use getFileAttributesAttribute() to get the files that should be cloned
     * by Bkwld\Cloner
     *
     * @return array The keys of all the attributes that store file references
     */
    public function getCloneableFileAttributes()
    {
        if (isset($this->cloneable_file_attributes)) {
            return $this->cloneable_file_attributes;
        }

        return $this->getFileAttributesAttribute();
    }

    /**
     * Automatically add classes to rows in listing tables in the admin
     *
     * @return string
     */
    public function getAdminRowClassAttribute()
    {
        $classes = [];

        // Add a visbility classs
        if ($this->public) {
            $classes[] = 'is-public';
        }

        // Add a soft-deleted class
        if (method_exists($this, 'trashed') && $this->trashed()) {
            $classes[] = 'is-trashed';
        }

        // Return all classes
        return implode(' ', $classes);
    }

    /**
     * Expose model attributes for comparison by the localization sidebar
     *
     * @return array
     */
    public function getAttributesForLocalizationComparisonAttribute()
    {
        $attributes = $this->getAttributes();
        if (method_exists($this, 'croppedImages')) {
            $attributes['images'] = $this->croppedImages(300);
        }
        return $attributes;
    }

    //---------------------------------------------------------------------------
    // Listing view, action-column accessors
    //---------------------------------------------------------------------------

    /**
     * Make the markup for the actions column of the admin listing view.  The
     * indivudal actions are stored in an array that is iterted through in the
     * view
     *
     * @param  array $data The data passed to a listing view
     * @return array
     */
    public function makeAdminActions($data)
    {
        $actions = [];

        if ($html = $this->makeVisibilityAction($data)) {
            $actions['visibility'] = $html;
        }

        if ($html = $this->makeEditAction($data)) {
            $actions['edit'] = $html;
        }

        if ($html = $this->makeViewAction($data)) {
            $actions['view'] = $html;
        }

        if ($html = $this->makeDeleteAction($data)) {
            $actions['delete'] = $html;
        }

        return $actions;
    }

    /**
     * Find by the slug and fail if missing.  Invokes methods from the
     * Sluggable trait.
     *
     * @param  string $string
     * @param  array  $columns
     * @return Illuminate\Database\Eloquent\Model
     *
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findBySlugOrFail(string $slug, array $columns = ['*'])
    {
        // Model not found, throw exception
        if (!$item = static::findBySlug($slug)) {
            throw (new ModelNotFoundException)->setModel(get_called_class());
        }

        // Return the model if visible
        $item->enforceVisibility();

        return $item;
    }

    //---------------------------------------------------------------------------
    // Utility methods
    //---------------------------------------------------------------------------

    /**
     * Throw exception if not public and no admin session
     *
     * @throws Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException
     */
    public function enforceVisibility()
    {
        if (array_key_exists('public', $this->getAttributes())
            && !$this->getAttribute('public')
            && !app('facilitador.user')
        ) {
            throw new AccessDeniedHttpException;
        }
    }

    /**
     * Deduce the source for the title of the model
     *
     * @return array
     */
    public function titleAttributes()
    {
        // Convert to an array so I can test for the presence of values. As an
        // object, it would throw exceptions
        $row = $this->getAttributes();

         // Name before title to cover the case of people with job titles
        if (isset($row['name'])) {
            return ['name'];
        }

        // Search full names if people-type fields
        if (isset($row['first_name']) && isset($row['last_name'])) {
            return ['first_name', 'last_name'];
        }

        // Standard location for the title
        if (isset($row['title'])) {
            return ['title'];
        }

        // Default to no searchable attributes
        return [];
    }

    /**
     * The pivot_id may be accessible at $this->pivot->id if the result was fetched
     * through a relationship OR it may be named pivot_id out of convention (something
     * currently done in Decoy_Base_Controller->get_index_child()).  This function
     * checks for either
     *
     * @return integer
     */
    public function pivotId()
    {
        if (!empty($this->pivot->id)) {
            return $this->pivot->id;
        }

        if (!empty($this->pivot_id)) {
            return $this->pivot_id;
        }

        return null;
    }

    /**
     * Add a field to the blacklist
     *
     * @param string $field
     */
    public function blacklist($field)
    {
        $this->guarded[] = $field;
    }

    /**
     * Criado por mim posteriormente
     */
    public function save(array $options = [])
    {
        parent::save($options);
    }
    

    /**
     * 
     */
    public static function createAndAssociate($dataOrPrimaryCode, $associateTo)
    {
        return static::associate(static::createIfNotExistAndReturn($dataOrPrimaryCode), $associateTo);
    }

    /**
     * Associa ao Um Modelo ao Outro
     */
    public static function associate($associateFrom, $associateTo)
    {
        $method = Str::plural(Str::lower(\class_basename($associateFrom)));
        if (method_exists($associateTo, $method)) {
            return call_user_func_array([$associateTo, $method], [])->save($associateFrom);
        }
        return false;
    }

    /**
     * 
     */
    public static function createIfNotExistAndReturn($dataOrPrimaryCode)
    {
        $modelFind = false;
        $keyName = (new static)->getKeyName();
        $data = ArrayModificator::convertToArrayWithIndex($dataOrPrimaryCode, $keyName);
        if (!$eloquentEntityForModel = ModelService::make(static::class)) {
            return static::firstOrCreate($data);
        }

        $data = DbalInclusor::includeDataFromEloquentEntity($eloquentEntityForModel, $data, $keyName);

        /**
         * Search from Indexes
         */
        $results = DbalExtractor::generateWhereFromData(
            $data,
            $eloquentEntityForModel->getIndexes()
        )->map(
            function ($query) use ($data) {
                if ($modelFind = static::where($query)->first()) {
                    Log::debug('[Support] ModelBase -> Encontrado com tributos: '.print_r($query, true).' e Data: '.print_r($data, true));
                    return DbalMergeator::mergeWithAttributes($modelFind, $data);
                }
                return false;
            }
        )->reject(
            function ($result) {
                return !$result;
            }
        );
        if ($results->isNotEmpty()) {
            return $results->first();
        }

        // // Cado nada de certo retorna o primeiro ou cria
        // // @debug Resolver essa gambiarra @todo
        // $eloquentEntityForModel->sendToDebug([$data, $keyName, $dataOrPrimaryCode, $eloquentEntityForModel]);
        return static::firstOrCreate($data);

    }



    /**
     * Help for Class
     */
    public function hasAttribute($attr)                                                                                                                                                          
    {                                                                                                                    
        return array_key_exists($attr, $this->attributes);
    }

    /**
     * Fiz pq tava quebrando @todo vericar, acho que vem do magento ou synfone
     */
    public function setModified($date)
    {
        return $date;
    }
    public function setCreated($date)
    {
        return $date;
    }
}
