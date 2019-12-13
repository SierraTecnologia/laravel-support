<?php

declare(strict_types=1);

namespace Support\Traits;

trait ConsoleTools
{
    /**
     * Publish package migrations.
     *
     * @return void
     */
    protected function publishesMigrations(string $package, bool $isModule = false): void
    {
        $namespace = str_replace('laravel-', '', $package);
        $namespace = str_replace(['/', '\\', '.', '_'], '-', $namespace);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/'.$package);

        if (file_exists($path = $basePath.'/database/migrations')) {
            $stubs = $this->app['files']->glob($path.'/*.php.stub');
            $existing = $this->app['files']->glob($this->app->databasePath('migrations/'.$package.'/*.php'));

            $migrations = collect($stubs)->flatMap(function ($migration) use ($existing, $package) {
                $sequence = mb_substr(basename($migration), 0, 2);
                $match = collect($existing)->first(function ($item, $key) use ($migration, $sequence) {
                    return mb_strpos($item, str_replace(['.stub', $sequence], '', basename($migration))) !== false;
                });

                return [$migration => $this->app->databasePath('migrations/'.$package.'/'.($match ? basename($match) : date('Y_m_d_His', time() + $sequence).str_replace(['.stub', $sequence], '', basename($migration))))];
            })->toArray();

            $this->publishes($migrations, $namespace.'-migrations');
        }
    }

    /**
     * Publish package config.
     *
     * @return void
     */
    protected function publishesConfig(string $package, bool $isModule = false): void
    {
        $namespace = str_replace('laravel-', '', $package);
        $namespace = str_replace(['/', '\\', '.', '_'], '-', $namespace);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/'.$package);

        if (file_exists($path = $basePath.'/config/config.php')) {
            $this->publishes([$path => $this->app->configPath(str_replace('-', '.', $namespace).'.php')], $namespace.'-config');
        }
    }

    /**
     * Publish package views.
     *
     * @return void
     */
    protected function publishesViews(string $package, bool $isModule = false): void
    {
        $namespace = str_replace('laravel-', '', $package);
        $namespace = str_replace(['/', '\\', '.', '_'], '-', $namespace);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/'.$package);

        if (file_exists($path = $basePath.'/resources/views')) {
            $this->publishes([$path => $this->app->resourcePath('views/vendor/'.$package)], $namespace.'-views');
        }
    }

    /**
     * Publish package lang.
     *
     * @return void
     */
    protected function publishesLang(string $package, bool $isModule = false): void
    {
        $namespace = str_replace('laravel-', '', $package);
        $namespace = str_replace(['/', '\\', '.', '_'], '-', $namespace);
        $basePath = $isModule ? $this->app->path($package)
            : $this->app->basePath('vendor/'.$package);

        if (file_exists($path = $basePath.'/resources/lang')) {
            $this->publishes([$path => $this->app->resourcePath('lang/vendor/'.$package)], $namespace.'-lang');
        }
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommandFolders($folders = false): void
    {
        if (!$folders) {
            $folders = $this->commandFolders;
        }
        if (is_string($folders)) {
            $folders = [$folders];
        }

        $commands = [];
        // Register artisan commands
        foreach ($folders as $value) {
            $commands = array_merge(
                $commands,
                $this->loadCommandsFromPath($value)
            );
        }

        $this->commands(array_values($commands));
    }

    /**
     * @param string $path
     * @return $this
     */
    private function loadCommandsFromPath($path) {
        $realPath = app_path($path);
        $commands = [];
        
        collect(scandir($realPath))
            ->each(function ($item) use ($path, $realPath) {
                if (in_array($item, ['.', '..'])) return;

                if (is_dir($realPath . $item)) {
                    $this->loadCommandsFromPath($path . $item . '/');
                }

                if (is_file($realPath . $item)) {
                    $item = str_replace('.php', '', $item);
                    $class = str_replace('/', '\\', "Facilitador\\{$path}$item");

                    if (class_exists($class)) {
                        $commands[] = $class;
                    }                  
                }
            });
    }



    /**
     * Configs Paths
     */
    private function getResourcesPath($folder)
    {
        return __DIR__.'/../resources/'.$folder;
    }

    private function getPublishesPath($folder)
    {
        return __DIR__.'/../publishes/'.$folder;
    }

    private function getDistPath($folder)
    {
        return __DIR__.'/../dist/'.$folder;
    }

    /**
     * Load Alias and Providers
     */
    private function setProviders()
    {
        $this->setDependencesAlias();
        (new Collection(self::$providers))->map(function ($provider) {
            if (class_exists($provider)) {
                $this->app->register($provider);
            }
        });
    }
    private function setDependencesAlias()
    {
        $loader = AliasLoader::getInstance();
        (new Collection(self::$aliasProviders))->map(function ($class, $alias) use ($loader) {
            $loader->alias($alias, $class);
        });
    }
}
