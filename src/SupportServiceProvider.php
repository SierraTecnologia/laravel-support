<?php

namespace Support;

// namespace Support\Components\Coders;

use App;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Config;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Events\RouteMatched;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Dusk\DuskServiceProvider;
use Log;
use Muleta\Traits\Providers\ConsoleTools;
use Muleta\Utils\Debugger\Classify;

use SierraTecnologia\Crypto\Services\Crypto;
use Support\Components\Coders\Model\Config as GenerateConfig;
use Support\Components\Coders\Model\Factory as ModelFactory;
use Support\Console\Commands\CodeModelsCommand;

use Support\Facades\Support as SupportFacade;
use Support\Services\ModelService;
use Support\Services\RegisterService;
use Support\Services\RepositoryService;
use Support\Support;

// class CodersServiceProvider extends ServiceProvider
class SupportServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    public $packageName = 'laravel-support';
    const pathVendor = 'sierratecnologia/laravel-support';
    // /**
    //  * @var bool
    //  */
    // protected $defer = true;

    public static $aliasProviders = [
        'SupportURL' => \Support\Facades\SupportURL::class,
    ];

    // public static $providers = [
    public static $providers = [
            /**
             * Layoults
             */
            \Pedreiro\PedreiroServiceProvider::class,
        ];

    public static $menuItens = [
        // [
        //     'text' => 'search',
        //     'search' => true,
        //     'topnav' => true,
        // ],
        [
            'text'        => 'Workspace',
            'url'         => 'painel',
            'dontSection'     => 'painel',
            'topnav' => true,
        ],
        [
            'text'        => 'Painel Administrativo',
            'url'         => 'admin',
            'dontSection'     => 'admin',
            'topnav' => true,
        ],
        [
            'text'        => 'RiCa',
            'url'         => 'rica',
            'dontSection'     => 'rica',
            'topnav' => true,
        ],
        // // [
        // //     'text'        => 'Painel Admin',
        // //     'route'       => 'rica.dashboard',
        // //     'icon'        => 'share',
        // //     'icon_color'  => 'red',
        // //     'label_color' => 'success',
        // //     // 'space'     => 'painel',
        // //     'level'       => 3,
        // //     'topnav' => true,
        // // ],
        // // [
        // //     'text'        => 'Changelog',
        // //     'route'       => 'admin.pedreiro.changelog',
        // //     'icon'        => 'fas fa-fw fa-user-secret',
        // // ],
        // // [
        // //     'text'        => 'Documentação',
        // //     'url'         => 'docs',
        // //     'icon'        => 'fas fa-fw fa-user-secret',
        // // ],
        // [
        //     'text'        => 'Business Admin',
        //     'route'       => 'rica.dashboard',
        //     'icon'        => 'share',
        //     'icon_color'  => 'red',
        //     'label_color' => 'success',
        //     // 'space'     => 'painel',
        //     'level'       => 3,
        //     'topnav' => true,
        // ],
        // [
        //     'text'        => 'RiCa Admin',
        //     'route'       => 'rica.dashboard',
        //     'icon'        => 'share',
        //     'icon_color'  => 'red',
        //     'label_color' => 'success',
        //     // 'space'     => 'painel',
        //     'level'       => 3,
        //     'topnav' => true,
        // ],
        // // [
        // //     'text' => 'System',
        // //     'icon' => 'fas fa-fw fa-search',
        // //     'icon_color' => "blue",
        // //     'label_color' => "success",
        // //     'level'       => 2, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        // // ],
        // 'System|450' => [
        //     [
        //         'text'        => 'Manager',
        //         'route'       => 'rica.dashboard',
        //         'icon'        => 'fas fa-fw fa-edit', //television
        //         'icon_color'  => 'blue',
        //         'label_color' => 'success',
        //         'space'      => 'rica',
        //         'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //         //  'access' => \App\Models\Role::$ADMIN
        //     ],
        //     [
        //         'text' => 'Manipule',
        //         'icon' => 'fas fa-fw fa-eye',
        //         'icon_color' => "blue",
        //         'label_color' => "success",
        //         'space'      => 'rica',
        //         'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //     ],
        //     [
        //         'text' => 'Debugger',
        //         'icon' => 'fas fa-fw fa-bug', //shield
        //         'icon_color' => "blue",
        //         'label_color' => "success",
        //         'space'      => 'rica',
        //         'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //     ],
        //     'Manipule' => [
        //         [
        //             'text'        => 'Crud Builder',
        //             'route'       => 'rica.facilitador.bread.index',
        //             'icon'        => 'fas fa-fw fa-eye',
        //             'icon_color'  => 'blue',
        //             'label_color' => 'success',
        //             'space'      => 'rica',
        //             'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //             //  'access' => \App\Models\Role::$ADMIN
        //         ],
        //         [
        //             'text'        => 'Database',
        //             'route'       => 'rica.facilitador.database.index',
        //             'icon'        => 'fas fa-fw fa-database',
        //             'icon_color'  => 'blue',
        //             'label_color' => 'success',
        //             'space'      => 'rica',
        //             'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //             //  'access' => \App\Models\Role::$ADMIN
        //         ],
        //         [
        //             'text'        => 'Commands',
        //             'route'       => 'rica.support.commands',
        //             'icon'        => 'fas fa-fw fa-asterisk',
        //             'icon_color'  => 'blue',
        //             'label_color' => 'success',
        //             'space'      => 'rica',
        //             'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //             //  'access' => \App\Models\Role::$ADMIN
        //         ],
        //         [
        //             'text'        => 'Routers',
        //             'route'       => 'rica.support.routers',
        //             'icon'        => 'fas fa-fw fa-folder',
        //             'icon_color'  => 'blue',
        //             'label_color' => 'success',
        //             'space'      => 'rica',
        //             'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //             //  'access' => \App\Models\Role::$ADMIN
        //         ],
        //     ],
        //     'Debugger' => [
        //         [
        //             'text'        => 'View Errors',
        //             'route'       => 'rica.dashboard',
        //             'icon'        => 'fas fa-fw fa-bug', //times, warning
        //             'icon_color'  => 'blue',
        //             'label_color' => 'success',
        //             'space'      => 'rica',
        //             'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        //             //  'access' => \App\Models\Role::$ADMIN
        //         ],
        //     ],
        // ],
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $event)
    {
        $this->loadTranslations();
        $this->loadViews();
        $this->publishMigrations();
        $this->publishAssets();
        $this->publishConfigs();

        // Register global and named middlewares
        $this->registerMiddlewares();

        $this->loadLogger();
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
        */

        
        // Register the routes.
        if (\Illuminate\Support\Facades\Config::get('site.core.register_routes', true) && !$this->app->routesAreCached()) {
            $this->app['support.router']->registerAll();
        }
        $event->listen(
            'facilitador.alerts.collecting',
            function () {
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


        // Build the Breadcrumbs store
        $this->app->singleton(
            'rica.breadcrumbs',
            function ($app) {
                $breadcrumbs = new \Pedreiro\Template\Layout\Breadcrumbs();
                $breadcrumbs->set($breadcrumbs->parseURL());

                return $breadcrumbs;
            }
        );

        // Registers explicit rotues and wildcarding routing
        $this->app->singleton(
            'support.router',
            function ($app) {
                $dir = \Illuminate\Support\Facades\Config::get('application.routes.rica', 'rica');

                return new \Support\Routing\Router($dir);
            }
        );

        // Register URL Generators as "SupportURL".
        $this->app->singleton(
            'support.url',
            function ($app) {
                return new \Support\Routing\UrlGenerator($app['request']->path());
            }
        );

        $loader = AliasLoader::getInstance();
        $loader->alias('Support', SupportFacade::class);

        $this->app->singleton(
            'support',
            function () {
                return new Support();
            }
        );


        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorBaseController',
            'Support\Http\Controllers\ExtendedBreadFormFields\ExtendedBreadFormFieldsController'
        );

        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorMediaController',
            'Support\Http\Controllers\ExtendedBreadFormFields\ExtendedBreadFormFieldsMediaController'
        );

        $this->registerModelFactory();

        $this->loadCommands();
        $this->loadMigrations();
        $this->loadConfigs();

        $this->app->singleton(
            \Support\Patterns\Parser\ComposerParser::class,
            function () {
                return new \Support\Patterns\Parser\ComposerParser();
            }
        );

        $this->app->singleton(
            \Support\Services\ApplicationService::class,
            function () {
                return new \Support\Services\ApplicationService();
            }
        );

        $this->loadHelpers();

        $this->loadServiceContainerSingletons();
        $this->loadServiceContainerRouteBinds();
        $this->loadServiceContainerBinds();
        $this->loadServiceContainerReplaceClasses();

        $this->loadExternalPackages();
        $this->loadLocalExternalPackages();
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

        if ($routeName != 'rica.dashboard') {
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
                    ->title(__('pedreiro::error.symlink_missing_title'))
                    ->text(__('pedreiro::error.symlink_missing_text'))
                    ->button(__('pedreiro::error.symlink_missing_button'), '?fix-missing-storage-symlink=1');
                SupportFacade::addAlert($alert);
            }
        }
    }

    protected function fixMissingStorageSymlink()
    {
        app('files')->link(storage_path('app/public'), public_path('storage'));

        if (file_exists(public_path('storage'))) {
            $alert = (new Alert('fixed-missing-storage-symlink', 'success'))
                ->title(__('pedreiro::error.symlink_created_title'))
                ->text(__('pedreiro::error.symlink_created_text'));
        } else {
            $alert = (new Alert('failed-fixing-missing-storage-symlink', 'danger'))
                ->title(__('pedreiro::error.symlink_failed_title'))
                ->text(__('pedreiro::error.symlink_failed_text'));
        }

        SupportFacade::addAlert($alert);
    }

    

    /**
     * Register Model Factory.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton(
            ModelFactory::class,
            function ($app) {
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
     *
     */
    private function loadLogger()
    {
        $level = env('APP_LOG_LEVEL_FOR_SUPPORT', 'warning');
        //@todo configurar adaptada dos leveis
        Config::set(
            'logging.channels.sitec-support',
            [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-support.log'),
            'level' => $level,
            ]
        );

        Config::set(
            'logging.channels.sitec-providers',
            [
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
            ],
            ['lang',  'sitec', 'sitec-lang', 'translations']
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
            ],
            ['views',  'sitec', 'sitec-views']
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
            ],
            ['public',  'sitec', 'sitec-public']
        );
    }

    protected function publishConfigs()
    {
        // Publish config files
        $this->publishes(
            [
                // Paths
                $this->getPublishesPath('config/generators') => config_path('generators'),
                $this->getPublishesPath('config/housekeepers') => config_path('housekeepers'),
                // Files
                $this->getPublishesPath('config/debug-server.php') => config_path('debug-server.php'),
                $this->getPublishesPath('config/debugbar.php') => config_path('debugbar.php'),
                $this->getPublishesPath('config/excel.php') => config_path('excel.php'),
                $this->getPublishesPath('config/tinker.php') => config_path('tinker.php'),
            ],
            ['config',  'sitec', 'sitec-config']
        );
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
            'modelClass',
            function ($value) {
                if (Crypto::isCrypto($value)) {
                    $value = Crypto::shareableDecrypt($value);
                }
                return $value;
                Log::debug('Route Bind ModelClass - '.$value);
                return new ModelService($value);
            }
        );
        Route::bind(
            'identify',
            function ($value) {
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
        // use Pedreiro\Models\Base;
        // @todo

        $this->app->bind(
            ModelService::class,
            function ($app) {
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
            RepositoryService::class,
            function ($app) {
                Log::debug('Bind Repository Service');
                $modelService = $app->make(ModelService::class);
                return new RepositoryService($modelService);
                // return $app->make(RepositoryService::class);
            }
        );

        $this->app->bind(
            RegisterService::class,
            function ($app) {
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

    /**
     * Register middlewares
     *
     * @return void
     */
    protected function registerMiddlewares()
    {
        if (config('siravel.login', true)) {
            $this->app['router']->aliasMiddleware('admin', \Support\Http\Middleware\AdminMiddleware::class);
        }

        // Register middleware individually
        foreach ([
            'facilitador.auth'          => \Support\Http\Middleware\Auth::class,
            'facilitador.edit-redirect' => \Support\Http\Middleware\EditRedirect::class,
            'facilitador.guest'         => \Support\Http\Middleware\Guest::class,
            'facilitador.save-redirect' => \Support\Http\Middleware\SaveRedirect::class,
        ] as $key => $class) {
            $this->app['router']->aliasMiddleware($key, $class);
        }

        // This group is used by public facilitador routes
        $this->app['router']->middlewareGroup(
            'facilitador.public',
            [
            'web',
            ]
        );

        // if (config('siravel.login', true)) {
        // The is the starndard auth protected group
        $this->app['router']->middlewareGroup(
            'facilitador.protected',
            [
                'web',
                'facilitador.auth',
                'facilitador.save-redirect',
                'facilitador.edit-redirect',
                ]
        );

        // Require a logged in admin session but no CSRF token
        $this->app['router']->middlewareGroup(
            'facilitador.protected_endpoint',
            [
                \App\Http\Middleware\EncryptCookies::class,
                \Illuminate\Session\Middleware\StartSession::class,
                'facilitador.auth',
                ]
        );

        // An open endpoint, like used by Zendcoder
        $this->app['router']->middlewareGroup(
            'facilitador.endpoint',
            [
                'api'
                ]
        );
        // }
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            ModelFactory::class,
            'rica.breadcrumbs',
            'support.router',
            'support.url',
        ];
    }
}
