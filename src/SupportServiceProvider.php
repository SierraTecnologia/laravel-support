<?php

namespace Support;
// namespace Support\Components\Coders;

use Support\Utils\Debugger\Classify;
use Support\Components\Coders\Model\Config as GenerateConfig;
use Illuminate\Filesystem\Filesystem;
use Support\Console\Commands\CodeModelsCommand;
use Support\Components\Coders\Model\Factory as ModelFactory;
use Config;
use Support\Traits\Providers\ConsoleTools;
use Log;
use App;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use SierraTecnologia\Crypto\Services\Crypto;

use Support\Services\RegisterService;
use Support\Services\RepositoryService;
use Support\Services\ModelService;

use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Support\ServiceProvider;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Validator;
use Support\Facades\Support as SupportFacade;
use Support\Elements\FormFields\MultipleImagesWithAttrsFormField;
use Support\Elements\FormFields\KeyValueJsonFormField;


// class CodersServiceProvider extends ServiceProvider
class SupportServiceProvider extends ServiceProvider
{
    use ConsoleTools;
    // /**
    //  * @var bool
    //  */
    // protected $defer = true;


    public static $menuItens = [
        [
            'text' => 'System',
            'icon' => 'fas fa-fw fa-search',
            'icon_color' => "blue",
            'label_color' => "success",
            'level'       => 2, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        ],
        'System|450' => [
            [
                'text'        => 'Manager',
                'route'       => 'facilitador.dashboard',
                'icon'        => 'fas fa-fw fa-edit', //television
                'icon_color'  => 'blue',
                'label_color' => 'success',
                'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                //  'access' => \App\Models\Role::$ADMIN
            ],
            [
                'text' => 'Manipule',
                'icon' => 'fas fa-fw fa-eye',
                'icon_color' => "blue",
                'label_color' => "success",
                'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
            ],
            [
                'text' => 'Debugger',
                'icon' => 'fas fa-fw fa-bug', //shield
                'icon_color' => "blue",
                'label_color' => "success",
                'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
            ],
            'Manipule' => [
                [
                    'text'        => 'Crud Builder',
                    'route'       => 'facilitador.bread.index',
                    'icon'        => 'fas fa-fw fa-eye',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    //  'access' => \App\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Database',
                    'route'       => 'facilitador.database.index',
                    'icon'        => 'fas fa-fw fa-database',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    //  'access' => \App\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Commands',
                    'route'       => 'facilitador.commands',
                    'icon'        => 'fas fa-fw fa-asterisk',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    //  'access' => \App\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Routers',
                    'route'       => 'facilitador.routers',
                    'icon'        => 'fas fa-fw fa-folder',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    //  'access' => \App\Models\Role::$ADMIN
                ],
            ],
            'Debugger' => [
                [
                    'text'        => 'View Errors',
                    'route'       => 'facilitador.dashboard',
                    'icon'        => 'fas fa-fw fa-bug', //times, warning
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    //  'access' => \App\Models\Role::$ADMIN
                ],
            ],
        ],
    ];
    public static $aliasProviders = [
        'Active' => \Support\Facades\Active::class,

        // Form field generation
        'Former' => \Former\Facades\Former::class,

    ];

    // public static $providers = [
    public static $providers = [
        /**
         * Layoults
         */
        \RicardoSierra\Minify\MinifyServiceProvider::class,
        \Collective\Html\HtmlServiceProvider::class,
        \Laracasts\Flash\FlashServiceProvider::class,

        /**
         * VEio pelo Decoy
         **/
        \Former\FormerServiceProvider::class,
        \Bkwld\Upchuck\ServiceProvider::class,

        /**
         * Outros
         */
        \Laravel\Tinker\TinkerServiceProvider::class,
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslations();
        $this->loadViews();
        $this->publishMigrations();
        $this->publishAssets();
        $this->publishConfigs();

        /**
         * Support Routes
         */
        Route::group([
            'namespace' => '\Support\Http\Controllers',
        ], function (/**$router**/) {
            require __DIR__.'/Routes/web.php';
        });


        /**
         * Load Active https://github.com/letrunghieu/active
         */
        // Update the instances each time a request is resolved and a route is matched
        $instance = app('active');
        app('router')->matched(
            function (RouteMatched $event) use ($instance) {
                $instance->updateInstances($event->route, $event->request);
            }
        );

        $this->loadLogger();

        // Add strip_tags validation rule
        Validator::extend('strip_tags', function ($attribute, $value) {
            return strip_tags($value) === $value;
        }, trans('validation.invalid_strip_tags'));

        /**
        // if ($this->app->runningInConsole()) {
        //     $this->publishes(
        //         [
        //         __DIR__.'/../../config/models.php' => config_path('models.php'),
        //         ], 'reliese-models'
        //     );

        //     $this->commands(
        //         [
        //         CodeModelsCommand::class,
        //         ]
        //     );
        // }

        // //ExtendedBreadFormFieldsServiceProvider
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'extended-fields');
            */
        // Config Former
        $this->configureFormer();

        $this->registerViewComposers();

        $event->listen(
            'facilitador.alerts.collecting', function () {
                $this->addStorageSymlinkAlert();
            }
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelFactory();

        $this->loadCommands();
        $this->loadMigrations();
        $this->loadConfigs();

        $this->app->singleton(
            \Support\Patterns\Parser\ComposerParser::class, function () {
                return new \Support\Patterns\Parser\ComposerParser();
            }
        );

        $this->app->singleton(
            \Support\Services\ApplicationService::class, function () {
                return new \Support\Services\ApplicationService();
            }
        );


        /**
         * Load Active https://github.com/letrunghieu/active
         */
        $this->app->singleton(
            'active',
            function ($app) {

                $instance = new Active($app['router']->getCurrentRequest());

                return $instance;
            }
        );

        $loader = AliasLoader::getInstance();
        $loader->alias('Support', SupportFacade::class);

        $this->app->singleton(
            'support', function () {
                return new Support();
            }
        );

        // ExtendedBreadFormFieldsServiceProvider

        Support::addFormField(KeyValueJsonFormField::class);
        Support::addFormField(MultipleImagesWithAttrsFormField::class);

        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorBaseController',
            'ExtendedBreadFormFields\Controllers\ExtendedBreadFormFieldsController'
        );

        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorMediaController',
            'ExtendedBreadFormFields\Controllers\ExtendedBreadFormFieldsMediaController'
        );


*/

        $this->loadHelpers();

        $this->loadServiceContainerSingletons();
        $this->loadServiceContainerRouteBinds();
        $this->loadServiceContainerBinds();
        $this->loadServiceContainerReplaceClasses();

        $this->loadExternalPackages();
        $this->loadLocalExternalPackages();


        $this->registerFormFields();
        $this->registerAlertComponents();

    }




    /**
     * Register view composers.
     */
    protected function registerViewComposers()
    {
        // Register alerts
        View::composer(
            'facilitador::*', function ($view) {
                $view->with('alerts', FacilitadorFacade::alerts());
            }
        );
    }

    /**
     * Add storage symlink alert.
     */
    protected function addStorageSymlinkAlert()
    {
        if (app('router')->current() !== null) {
            $currentRouteAction = app('router')->current()->getAction();
        } else {
            $currentRouteAction = null;
        }
        $routeName = is_array($currentRouteAction) ? Arr::get($currentRouteAction, 'as') : null;

        if ($routeName != 'facilitador.dashboard') {
            return;
        }

        $storage_disk = (!empty(\Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk'))) ? \Illuminate\Support\Facades\Config::get('sitec.facilitador.storage.disk') : 'public';

        if (request()->has('fix-missing-storage-symlink')) {
            if (file_exists(public_path('storage'))) {
                if (@readlink(public_path('storage')) == public_path('storage')) {
                    rename(public_path('storage'), 'storage_old');
                }
            }

            if (!file_exists(public_path('storage'))) {
                $this->fixMissingStorageSymlink();
            }
        } elseif ($storage_disk == 'public') {
            if (!file_exists(public_path('storage')) || @readlink(public_path('storage')) == public_path('storage')) {
                $alert = (new Alert('missing-storage-symlink', 'warning'))
                    ->title(__('facilitador::error.symlink_missing_title'))
                    ->text(__('facilitador::error.symlink_missing_text'))
                    ->button(__('facilitador::error.symlink_missing_button'), '?fix-missing-storage-symlink=1');
                FacilitadorFacade::addAlert($alert);
            }
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title(__('facilitador::error.symlink_created_title'))
                ->text(__('facilitador::error.symlink_created_text'));
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title(__('facilitador::error.symlink_failed_title'))
                ->text(__('facilitador::error.symlink_failed_text'));
        }

        FacilitadorFacade::addAlert($alert);
    }

    /**
     * Register alert components.
     */
    protected function registerAlertComponents()
    {
        $components = ['title', 'text', 'button'];

        foreach ($components as $component) {
            $class = 'Support\\Elements\\Alert\\'.ucfirst(Str::camel($component)).'Component';

            $this->app->bind("facilitador.alert.components.{$component}", $class);
        }
    }




    

    /**
     * Register Model Factory.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton(
            ModelFactory::class, function ($app) {
                return new ModelFactory(
                    $app->make('db'),
                    $app->make(Filesystem::class),
                    new Classify(),
                    new GenerateConfig($app->make('config')->get('models'))
                );
            }
        );
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [ModelFactory::class];
    }




    /**
     * 
     */
    private function loadLogger()
    {
        $level = env('APP_LOG_LEVEL_FOR_SUPPORT', 'warning');
        //@todo configurar adaptada dos leveis
        Config::set(
            'logging.channels.sitec-support', [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-support.log'),
            'level' => $level,
            ]
        );

        Config::set(
            'logging.channels.sitec-providers', [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-providers.log'),
            'level' => $level,
            ]
        );
    }

    /****************************************************************************************************
     * ************************************************* NO BOOT *************************************
     ****************************************************************************************************/
    
    protected function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang/vendor/support')
            ], ['lang',  'sitec', 'sitec-lang', 'translations']
        );

        // Load translations
        $this->loadTranslationsFrom($this->getResourcesPath('lang'), 'support');
    }

    protected function loadViews()
    {
        // View namespace
        $viewsPath = $this->getResourcesPath('views');
        $this->loadViewsFrom($viewsPath, 'support');
        $this->publishes(
            [
            $viewsPath => base_path('resources/views/vendor/support'),
            ], ['views',  'sitec', 'sitec-views']
        );

    }

    protected function publishMigrations()
    {
        
       
    }
       
    protected function publishAssets()
    {
        
        // Publish support css and js to public directory
        $this->publishes(
            [
            $this->getDistPath('support') => public_path('assets/support')
            ], ['public',  'sitec', 'sitec-public']
        );

    }

    protected function publishConfigs()
    {
        // Publish config files
        $this->publishes(
            [
                // Paths
                $this->getPublishesPath('config/elements') => config_path('elements'),
                $this->getPublishesPath('config/generators') => config_path('generators'),
                $this->getPublishesPath('config/housekeepers') => config_path('housekeepers'),
                // Files
                $this->getPublishesPath('config/debug-server.php') => config_path('debug-server.php'),
                $this->getPublishesPath('config/debugbar.php') => config_path('debugbar.php'),
                $this->getPublishesPath('config/excel.php') => config_path('excel.php'),
                $this->getPublishesPath('config/tinker.php') => config_path('tinker.php'),
            ], ['config',  'sitec', 'sitec-config']
        );

    }

    /**
     * Config Former
     *
     * @return void
     */
    protected function configureFormer()
    {
        // Use Bootstrap 3
        Config::set('former.framework', 'TwitterBootstrap3');

        // Reduce the horizontal form's label width
        Config::set('former.TwitterBootstrap3.labelWidths', []);

        // @todo desfazer pq da erro qnd falta tabela model_translactions
        // // Change Former's required field HTML
        // Config::set(
        //     'former.required_text', ' <span class="glyphicon glyphicon-exclamation-sign js-tooltip required" title="' .
        //     __('facilitador::login.form.required') . '"></span>'
        // );

        // Make pushed checkboxes have an empty string as their value
        Config::set('former.unchecked_value', '');

        // Add Decoy's custom Fields to Former so they can be invoked using the "Former::"
        // namespace and so we can take advantage of sublassing Former's Field class.
        $this->app['former.dispatcher']->addRepository('Support\\Elements\\Fields\\');
    }

    

    /****************************************************************************************************
     * ************************************************* NO REGISTER *************************************
     ****************************************************************************************************/


    protected function loadCommands()
    {

 
        // Outros
        // Register commands
        $this->registerCommandFolders(
            [
            base_path('vendor/sierratecnologia/laravel-support/src/Console/Commands') => '\Support\Console\Commands',
            ]
        );

    }
       
       
    protected function loadMigrations()
    {
        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
       
    }
    protected function loadConfigs()
    {
        
        // // Merge own configs into user configs 
        $this->mergeConfigFrom($this->getPublishesPath('config/elements/fields.php'), 'elements.fields');
        $this->mergeConfigFrom($this->getPublishesPath('config/generators/core.php'), 'generators.core');
        $this->mergeConfigFrom($this->getPublishesPath('config/generators/loader.php'), 'generators.loader');
        $this->mergeConfigFrom($this->getPublishesPath('config/generators/model.php'), 'generators.model');
        $this->mergeConfigFrom($this->getPublishesPath('config/housekeepers/components.php'), 'housekeepers.components');
        $this->mergeConfigFrom($this->getPublishesPath('config/housekeepers/encode.php'), 'housekeepers.encode');
        $this->mergeConfigFrom($this->getPublishesPath('config/debug-server.php'), 'debug-server');
        $this->mergeConfigFrom($this->getPublishesPath('config/debugbar.php'), 'debugbar');
        $this->mergeConfigFrom($this->getPublishesPath('config/excel.php'), 'excel');
        $this->mergeConfigFrom($this->getPublishesPath('config/tinker.php'), 'tinker');
    }

    /**
     * Load helpers.
     */
    protected function loadHelpers()
    {
        foreach (glob(__DIR__.'/Helpers/*.php') as $filename) {
            include_once $filename;
        }
    }

    protected function loadServiceContainerSingletons()
    {

    }
    protected function loadServiceContainerRouteBinds()
    {
        
        
        /**
         * @todo Ta passando duas vezes por aqui
         */
        Route::bind(
            'modelClass', function ($value) {
                if (Crypto::isCrypto($value)) {
                    $value = Crypto::shareableDecrypt($value);
                }
                return $value;
                Log::debug('Route Bind ModelClass - '.$value);
                return new ModelService($value);
            }
        );
        Route::bind(
            'identify', function ($value) {
                if (Crypto::isCrypto($value)) {
                    $value = Crypto::shareableDecrypt($value);
                }
                return $value;
                Log::debug('Route Bind Identify - '.$value);
                // throw new Exception(
                //     "Essa classe deveria ser uma string: ".print_r($modelClass, true),
                //     400
                // );
                return new RegisterService($value);
            }
        );
    }

    protected function loadServiceContainerBinds()
    {

        // /**
        //  * Cryptos
        //  * @todo Verificar pq isso ta aqui
        //  */
        // $this->app->bind('CryptoService', function ($app) {
        //     return new CryptoService();
        // });



        // Arrumar um jeito de fazer o Base do facilitador passar por cima do support
        // use Support\Models\Base;
        // @todo

        $this->app->bind(
            ModelService::class, function ($app) {
                // return $app->make(ModelService::class);
                $modelClass = false;
                if (isset($app['router']->current()->parameters['modelClass'])) {
                    $modelClass = $app['router']->current()->parameters['modelClass'];
                    if (Crypto::isCrypto($app['router']->current()->parameters['modelClass'])) {
                        $modelClass = Crypto::shareableDecrypt($app['router']->current()->parameters['modelClass']);
                        if (empty($modelClass)) {
                            $modelClass = $app['router']->current()->parameters['modelClass'];
                        }
                    }
                }
                // dd(
                //     $app['router']->current()->parameters['modelClass'],
                //     Crypto::isCrypto($app['router']->current()->parameters['modelClass']),
                //     $modelClass
                // );
                // dd('@todo', 
                //     $modelClass, $app['router']->current()->parameters['modelClass'], Crypto::shareableDecrypt($app['router']->current()->parameters['modelClass']),
                //     auth()->id()
                // );
                // @todo Ver Como resolver isso aqui
                Log::debug('Bind Model Service - '.$modelClass);

                return new ModelService($modelClass);
            }
        );

        $this->app->bind(
            RepositoryService::class, function ($app) {
                Log::debug('Bind Repository Service');
                $modelService = $app->make(ModelService::class);
                return new RepositoryService($modelService);
                // return $app->make(RepositoryService::class);
            }
        );

        $this->app->bind(
            RegisterService::class, function ($app) {
                // return $app->make(RegisterService::class);
                $identify = '';
                if (isset($app['router']->current()->parameters['identify'])) {
                    $identify = $app['router']->current()->parameters['identify'];
                    if (Crypto::isCrypto($app['router']->current()->parameters['identify'])) {
                        $identify = Crypto::shareableDecrypt($app['router']->current()->parameters['identify']);
                        if (empty($identify)) {
                            $identify = $app['router']->current()->parameters['identify'];
                        }
                    }
                }

                // if (empty($identify)) {
                //     dd(
                //         $identify,
                //         $app['router']->current()->parameters,
                //         Crypto::isCrypto($app['router']->current()->parameters['identify'])
                //     );
                // }

                Log::debug('Bind Register Service - '.$identify);
                return new RegisterService($identify);
            }
        );
    }

    protected function loadServiceContainerReplaceClasses()
    {

        
        // $this->app->when(ModelService::class)
        //     ->needs('$modelClass')
        //   ->give(function ($modelClassValue) {
        //       $request = $modelClassValue['request'];
        //         dd($request->has('modelClassValue'));
        //     //   dd();
        //       return $modelClassValue;
        //   });
    }


    protected function loadExternalPackages()
    {
        $this->setProviders();
    }

    protected function loadLocalExternalPackages()
    {

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
        if ($this->app->environment('local')) { 
            if (class_exists(DebugService::class)) {
                $this->app->register(DebugService::class);
            }
            if (class_exists(IdeHelperServiceProvider::class)) {
                $this->app->register(IdeHelperServiceProvider::class);
            }
        }
    }
    protected function registerFormFields()
    {
        $formFields = [
            'checkbox',
            'multiple_checkbox',
            'color',
            'date',
            'file',
            'image',
            'multiple_images',
            'media_picker',
            'number',
            'password',
            'radio_btn',
            'rich_text_box',
            'code_editor',
            'markdown_editor',
            'select_dropdown',
            'select_multiple',
            'text',
            'text_area',
            'time',
            'timestamp',
            'hidden',
            'coordinates',
        ];

        foreach ($formFields as $formField) {
            $class = Str::studly("{$formField}_handler");

            FacilitadorFacade::addFormField("Support\\Elements\\FormFields\\{$class}");
        }

        FacilitadorFacade::addAfterFormField(DescriptionHandler::class);

        event(new FormFieldsRegistered($formFields));
    }
}
