<?php

namespace Support\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App;
use URL;
use View;
use Facilitador;
use Event;
use Former;
use Request;
use SupportURL;
use Redirect;
use Response;
use stdClass;
use Validator;
use Route;
use Illuminate\Support\Str;
use Support\Template\Input\Search;
use Bkwld\Library\Utils\File;
use Support\Template\Input\Sidebar;
use Support\Elements\Fields\Listing;
use Translation\Template\Localize;
use Support\Template\Input\Position;
use Support\Template\Input\NestedModels;
use Support\Template\Input\ModelValidator;
use Support\Models\Base as BaseModel;
use Support\Exceptions\ValidationFail;
use Bkwld\Library\Laravel\Validator as BkwldLibraryValidator;

class Controller extends BaseController
{

    /**
     * The controller class name. Ex: Admin\PostsController
     *
     * @var string
     */
    protected $controller;

    /**
     * The HTML title, shown in header of the vie. Ex: News Posts
     *
     * @var string
     */
    protected $title;

    /**
     * The text description of what this controller manages, shown in the header.
     * Ex: "Relevant news about the brand"
     *
     * @var string
     */
    protected $description;

    //---------------------------------------------------------------------------
    // Constructing
    //---------------------------------------------------------------------------

    /**
     * A view instance to use as the layout
     *
     * @var Illuminate\Contracts\View\Factory
     */
    protected $layout;
    protected $loadLayout = false;

    /**
     * Populate protected properties on init
     */
    public function __construct()
    {
        $this->loadLayout();
    }
    public function loadLayout()
    {
        if ($this->loadLayout) {
            return ;
        }
        // Set the layout from the Config file
        $this->layout = View::make(\Illuminate\Support\Facades\Config::get('painel.core.layout', 'support::layouts.adminlte.master'));

        // Store the controller class for routing
        $this->controller = get_class($this);

        // Get the controller name
        $controller_name = $this->controllerName($this->controller);

        // Make a default title based on the controller name
        if (empty($this->title)) {
            $this->title = $this->title($controller_name);
        }

        $requestUrl = str_replace(['https://', 'http://'], '', Request::url());
        if (Route::has('rica.dashboard')) {
            $requestUrl = explode('/', str_replace(route('rica.dashboard'), '', $requestUrl));
            array_shift($requestUrl);
            $this->layout->segments = array_filter($requestUrl);
            $this->layout->url = route('rica.dashboard');
        }

        $this->loadLayout = true;
        return ;
    }


    protected function getFeature($model)
    {
        $isModels = [
            'page',
            'link',
            'menu',
            'plan',
        ];
        if (in_array($model, $isModels)) {
            return 'Negocios';
        }

        return 'System';

    }

    /**
     * Pass controller properties that are used by the layout and views through
     * to the view layer
     *
     * @param  mixed $content string view name or an HtmlObject / View object
     * @param  array $vars    Key value pairs passed to the content view
     * @return Illuminate\View\View
     */
    protected function populateView($content, $vars = [])
    {
        $this->loadLayout();
        
        // The view
        if (is_string($content)) {
            $this->layout->content = View::make($content);
        } else {
            $this->layout->content = $content;
        }
        
        // Set vars
        $this->layout->title = $this->title();
        $this->layout->description = $this->description();
        View::share('controller', $this->controller);

        // Make sure that the content is a Laravel view before applying vars.
        // to it.  In the case of the index view, `content` is a Fields\Listing
        // instance, not a Laravel view
        if (is_a($this->layout->content, 'Illuminate\View\View')) {
            $this->layout->content->with($vars);
        }

        // Return the layout View
        return $this->layout;
    }

    //---------------------------------------------------------------------------
    // Getter/setter
    //---------------------------------------------------------------------------

    /**
     * Get the controller name only, without the namespace (like Admin\) or
     * suffix (like Controller).
     *
     * @param  string $class ex: App\Http\Controllers\Admin\News
     * @return string ex: News
     */
    public function controllerName($class = null)
    {
        $name = $class ? $class : get_class($this);
        $name = preg_replace(
            '#^('.preg_quote('Facilitador\Http\Controllers\Admin\\')
            .'|'.preg_quote('App\Http\Controllers\Admin\\').')#', '', $name
        );

        return $name;
    }

    /**
     * Get the title for the controller based on the controller name.  Basically,
     * it's a de-studdly-er
     *
     * @param  string $controller_name ex: 'Admins' or 'CarLovers'
     * @return string ex: 'Admins' or 'Car Lovers'
     */
    public function title($controller_name = null)
    {
         // For when this is invoked as a getter for $this->title
        if (!$controller_name) {
            return $this->title;
        }

        // Do the de-studlying
        preg_match_all('#[a-z]+|[A-Z][a-z]*#', $controller_name, $matches);

        return implode(" ", $matches[0]);
    }

    /**
     * Get the description for a controller
     *
     * @return string
     */
    public function description()
    {
        return $this->description;
    }
}