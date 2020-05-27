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
                'text' => 'Debugger',
                'icon' => 'fas fa-fw fa-bomb',
                'icon_color' => "blue",
                'label_color' => "success",
            ],
            [
                'text' => 'Manipule',
                'icon' => 'fas fa-fw fa-bomb',
                'icon_color' => "blue",
                'label_color' => "success",
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
            ],
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
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
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerModelFactory();

        $this->loadMigrations();

        $this->app->singleton(
            \Support\Services\DatabaseService::class, function () {
                return new \Support\Services\DatabaseService(\Illuminate\Support\Facades\Config::get('sitec.discover.models_alias', []), new \Support\Components\Coders\Parser\ComposerParser);
            }
        );

        $this->app->singleton(
            \Support\Services\ApplicationService::class, function () {
                return new \Support\Services\ApplicationService(\Illuminate\Support\Facades\Config::get('sitec.discover.models_alias', []), new \Support\Components\Coders\Parser\ComposerParser);
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



        // Outros
        // Register commands
        $this->registerCommandFolders(
            [
            base_path('vendor/sierratecnologia/laravel-support/src/Console/Commands') => '\Support\Console\Commands',
            ]
        );

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
       
    protected function loadMigrations()
    {
        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
       
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
}
