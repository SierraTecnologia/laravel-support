<?php

namespace Support;
// namespace Support\Components\Coders;

use Support\Utils\Debugger\Classify;
use Support\Components\Coders\Model\Config as GenerateConfig;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
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


use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Barryvdh\Debugbar\ServiceProvider as DebugService;
use Laravel\Dusk\DuskServiceProvider;

// class CodersServiceProvider extends ServiceProvider
class SupportServiceProvider extends ServiceProvider
{
    use ConsoleTools;
    // /**
    //  * @var bool
    //  */
    // protected $defer = true;


    public static $menuItens = [
        'System|450' => [
            [
                'text'        => 'Manager',
                'route'       => 'facilitador.dashboard',
                'icon'        => 'fas fa-fw fa-industry',
                'icon_color'  => 'blue',
                'label_color' => 'success',
                // 'access' => \App\Models\Role::$ADMIN
            ],
            [
                'text' => 'Manipule',
                'icon' => 'fas fa-fw fa-bomb',
                'icon_color' => "blue",
                'label_color' => "success",
            ],
            [
                'text' => 'Debugger',
                'icon' => 'fas fa-fw fa-bomb',
                'icon_color' => "blue",
                'label_color' => "success",
            ],
            'Manipule' => [
                [
                    'text'        => 'Bread',
                    'route'       => 'facilitador.bread.index',
                    'icon'        => 'fas fa-fw fa-industry',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    // 'access' => \App\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Database',
                    'route'       => 'facilitador.database.index',
                    'icon'        => 'fas fa-fw fa-industry',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    // 'access' => \App\Models\Role::$ADMIN
                ],
                [
                    'text'        => 'Commands',
                    'route'       => 'facilitador.commands',
                    'icon'        => 'fas fa-fw fa-industry',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    // 'access' => \App\Models\Role::$ADMIN
                ],
            ],
            'Debugger' => [
                [
                    'text'        => 'View Errors',
                    'route'       => 'facilitador.dashboard',
                    'icon'        => 'fas fa-fw fa-industry',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    // 'access' => \App\Models\Role::$ADMIN
                ],
            ],
        ],
    ];
    public static $aliasProviders = [
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

        // $this->loadLogger();

        // //ExtendedBreadFormFieldsServiceProvider
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'extended-fields');


        // // CrudMaker


        // $this->publishes(
        //     [
        //     __DIR__.'/Templates/Laravel' => base_path('resources/crudmaker'),
        //     __DIR__.'/../publishes/config/crudmaker.php' => base_path('config/crudmaker.php'),
        //     ]
        // );

        // // FormMaker

        // $this->publishes(
        //     [
        //     __DIR__.'/../publishes/config/form-maker.php' => base_path('config/form-maker.php'),
        //     ]
        // );
        

        // /*
        // |--------------------------------------------------------------------------
        // | Blade Directives
        // |--------------------------------------------------------------------------
        // *//**

        // // Form Maker
        // Blade::directive(
        //     'form_maker_table', function ($expression) {
        //         return "<?php echo FormMaker::fromTable($expression); ?>";
        //     }
        // );

        // Blade::directive(
        //     'form_maker_array', function ($expression) {
        //         return "<?php echo FormMaker::fromArray($expression); ?>";
        //     }
        // );

        // Blade::directive(
        //     'form_maker_object', function ($expression) {
        //         return "<?php echo FormMaker::fromObject($expression); ?>";
        //     }
        // );

        // Blade::directive(
        //     'form_maker_columns', function ($expression) {
        //         return "<?php echo FormMaker::getTableColumns($expression); ?>";
        //     }
        // );

        // // Label Maker
        // Blade::directive(
        //     'input_maker_label', function ($expression) {
        //         return "<?php echo InputMaker::label($expression); ?>";
        //     }
        // );

        // Blade::directive(
        //     'input_maker_create', function ($expression) {
        //         return "<?php echo InputMaker::create($expression); ?>";
        //     }
        // ); */
        // Config Former
        $this->configureFormer();
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
        //ExtendedBreadFormFieldsServiceProvider

        Facilitador::addFormField(KeyValueJsonFormField::class);
        Facilitador::addFormField(MultipleImagesWithAttrsFormField::class);

        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorBaseController',
            'ExtendedBreadFormFields\Controllers\ExtendedBreadFormFieldsController'
        );

        $this->app->bind(
            'Facilitador\Http\Controllers\FacilitadorMediaController',
            'ExtendedBreadFormFields\Controllers\ExtendedBreadFormFieldsMediaController'
        );


        // CRUDMaker

        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        *//**

        if (class_exists('Illuminate\Foundation\AliasLoader')) {
            $this->app->register(FormMakerProvider::class);
        }

        /*
        |--------------------------------------------------------------------------
        | Register the Commands
        |--------------------------------------------------------------------------
        *//**

        $this->commands(
            [
            \Siravel\Console\Commands\CrudMaker\CrudMaker::class,
            \Siravel\Console\Commands\CrudMaker\TableCrudMaker::class,
            ]
        );


        // FormMaker

        /*
        |--------------------------------------------------------------------------
        | Providers
        |--------------------------------------------------------------------------
        *//**

        $this->app->register(\Collective\Html\HtmlServiceProvider::class);

        /*
        |--------------------------------------------------------------------------
        | Register the Utilities
        |--------------------------------------------------------------------------
        *//**

        $this->app->singleton(
            'FormMaker', function () {
                return new FormMaker();
            }
        );

        $this->app->singleton(
            'InputMaker', function () {
                return new InputMaker();
            }
        );

        $loader = AliasLoader::getInstance();

        $loader->alias('FormMaker', \Facilitador\FormMaker\Facades\FormMaker::class);
        $loader->alias('InputMaker', \Facilitador\FormMaker\Facades\InputMaker::class);

        // Thrid party
        $loader->alias('Form', \Collective\Html\FormFacade::class);
        $loader->alias('HTML', \Collective\Html\HtmlFacade::class);
 */

        $this->loadHelpers();

        $this->loadServiceContainerSingletons();
        $this->loadServiceContainerRouteBinds();
        $this->loadServiceContainerBinds();
        $this->loadServiceContainerReplaceClasses();

        $this->loadExternalPackages();
        $this->loadLocalExternalPackages();



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
        
        // // Publish config files
        // $this->publishes(
        //     [
        //     // Paths
        //     $this->getPublishesPath('config/sitec') => config_path('sitec'),
        //     // Files
        //     $this->getPublishesPath('config/crudmaker.php') => config_path('crudmaker.php'),
        //     $this->getPublishesPath('config/debug-server.php') => config_path('debug-server.php'),
        //     $this->getPublishesPath('config/debugbar.php') => config_path('debugbar.php'),
        //     $this->getPublishesPath('config/eloquentfilter.php') => config_path('eloquentfilter.php'),
        //     $this->getPublishesPath('config/excel.php') => config_path('excel.php'),
        //     $this->getPublishesPath('config/form-maker.php') => config_path('form-maker.php'),
        //     $this->getPublishesPath('config/gravatar.php') => config_path('gravatar.php'),
        //     $this->getPublishesPath('config/tinker.php') => config_path('tinker.php'),
        //     $this->getPublishesPath('config/facilitador-hooks.php') => config_path('facilitador-hooks.php'),
        //     $this->getPublishesPath('config/facilitador.php') => config_path('facilitador.php')
        //     ], ['config',  'sitec', 'sitec-config']
        // );

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
        $this->app['former.dispatcher']->addRepository('Facilitador\\Fields\\');
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
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/discover.php'), 'sitec.discover');
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/generator.php'), 'sitec.generator');
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/facilitador.php'), 'sitec.facilitador');
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/site.php'), 'sitec.site');
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/core.php'), 'sitec.core');
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/encode.php'), 'sitec.encode');
        // // @todo Remover mais pra frente esse aqui
        // $this->mergeConfigFrom($this->getPublishesPath('config/sitec/attributes.php'), 'sitec.attributes');
        
        // $this->mergeConfigFrom($this->getPublishesPath('config/crudmaker.php'), 'crudmaker');
        // $this->mergeConfigFrom($this->getPublishesPath('config/debug-server.php'), 'debug-server');
        // $this->mergeConfigFrom($this->getPublishesPath('config/debugbar.php'), 'debugbar');
        // $this->mergeConfigFrom($this->getPublishesPath('config/eloquentfilter.php'), 'eloquentfilter');
        // $this->mergeConfigFrom($this->getPublishesPath('config/excel.php'), 'excel');
        // $this->mergeConfigFrom($this->getPublishesPath('config/form-maker.php'), 'form-maker');
        // $this->mergeConfigFrom($this->getPublishesPath('config/gravatar.php'), 'gravatar');
        // $this->mergeConfigFrom($this->getPublishesPath('config/tinker.php'), 'tinker');
        // // $this->mergeConfigFrom($this->getPublishesPath('config/facilitador-hooks.php'), 'facilitador-hooks');
        // // $this->mergeConfigFrom($this->getPublishesPath('config/facilitador.php'), 'facilitador');
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
                Log::channel('sitec-providers')->debug('Route Bind ModelClass - '.Crypto::shareableDecrypt($value).' - '.$value);
                return new ModelService(Crypto::shareableDecrypt($value));
            }
        );
        Route::bind(
            'identify', function ($value) {
                Log::channel('sitec-providers')->debug('Route Bind Identify - '.Crypto::shareableDecrypt($value).' - '.$value);
                // throw new Exception(
                //     "Essa classe deveria ser uma string: ".print_r($modelClass, true),
                //     400
                // );
                return new RegisterService(Crypto::shareableDecrypt($value));
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
                $modelClass = false;
                if (isset($app['router']->current()->parameters['modelClass'])) {
                    $modelClass = Crypto::shareableDecrypt($app['router']->current()->parameters['modelClass']);

                    if (empty($modelClass)) {
                        $modelClass = $app['router']->current()->parameters['modelClass'];
                    }
                }

                // dd('@todo', 
                //     $modelClass, $app['router']->current()->parameters['modelClass'], Crypto::shareableDecrypt($app['router']->current()->parameters['modelClass']),
                //     auth()->id()
                // );
                // @todo Ver Como resolver isso aqui

                Log::channel('sitec-providers')->debug('Bind Model Service - '.$modelClass);

                return new ModelService($modelClass);
            }
        );

        $this->app->bind(
            RepositoryService::class, function ($app) {
                Log::channel('sitec-providers')->debug('Bind Repository Service');
                $modelService = $app->make(ModelService::class);
                return new RepositoryService($modelService);
            }
        );

        $this->app->bind(
            RegisterService::class, function ($app) {
                $identify = '';
                if (isset($app['router']->current()->parameters['identify'])) {
                    $identify = Crypto::shareableDecrypt($app['router']->current()->parameters['identify']);
                }

                Log::channel('sitec-providers')->debug('Bind Register Service - '.$identify);
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
            $this->app->register(DebugService::class);
            // $this->app->register(IdeHelperServiceProvider::class);
        }
    }
}
