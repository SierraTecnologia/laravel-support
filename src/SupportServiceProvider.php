<?php

namespace Support;
// namespace Support\Generate\Coders;

use Support\Generate\Support\Classify;
use Support\Generate\Coders\Model\Config as GenerateConfig;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Support\Console\Commands\CodeModelsCommand;
use Support\Generate\Coders\Model\Factory as ModelFactory;
use Config;

// class CodersServiceProvider extends ServiceProvider
class SupportServiceProvider extends ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/models.php' => config_path('models.php'),
            ], 'reliese-models');

            $this->commands([
                CodeModelsCommand::class,
            ]);
        }

        $this->loadLogger();
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

        $this->app->singleton(\Support\Services\DatabaseService::class, function () {
            return new \Support\Services\DatabaseService(config('sitec.discover.models_alias', []), new \Support\Parser\ComposerParser);
        });
    }







    

    /**
     * Register Model Factory.
     *
     * @return void
     */
    protected function registerModelFactory()
    {
        $this->app->singleton(ModelFactory::class, function ($app) {
            return new ModelFactory(
                $app->make('db'),
                $app->make(Filesystem::class),
                new Classify(),
                new GenerateConfig($app->make('config')->get('models'))
            );
        });
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
        Config::set('logging.channels.sitec-support', [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-support.log'),
            'level' => $level,
        ]);

        Config::set('logging.channels.sitec-providers', [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-providers.log'),
            'level' => $level,
        ]);
    }
}
