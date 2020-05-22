<?php

declare(strict_types=1);

namespace Support\Traits\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Collection;
use Support\Utils\Extratores\FileExtractor;
use Support\Utils\Extratores\ClasserExtractor;

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

            $migrations = collect($stubs)->flatMap(
                function ($migration) use ($existing, $package) {
                    $sequence = mb_substr(basename($migration), 0, 2);
                    $match = collect($existing)->first(
                        function ($item, $key) use ($migration, $sequence) {
                            return mb_strpos($item, str_replace(['.stub', $sequence], '', basename($migration))) !== false;
                        }
                    );

                    return [$migration => $this->app->databasePath('migrations/'.$package.'/'.($match ? basename($match) : date('Y_m_d_His', time() + $sequence).str_replace(['.stub', $sequence], '', basename($migration))))];
                }
            )->toArray();

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
    protected function registerCommandFolders($folders): void
    {
        // if (!$folders) {
        //     $folders = $this->commandFolders;
        // }
        // if (is_string($folders)) {
        //     $folders = [$folders];
        // }

        $commands = [];
        // Register artisan commands
        foreach ($folders as $key => $value) {
            $commands = array_merge(
                $commands,
                $this->loadCommandsFromPath($key, $value)
            );
        }
        $this->commands(array_values($commands));
    }

    /**
     * @param  string $path
     * @return $this
     */
    private function loadCommandsFromPath($path, $namespace)
    {
        $path = $path.'/';
        $commands = [];
        
        collect(scandir($path))
            ->each(
                function ($item) use ($path, $namespace, &$commands) {
                    if (in_array($item, ['.', '..'])) { return;
                    }
                
                    if (is_dir($path . $item)) {
                        $commands = array_merge(
                            $commands,
                            $this->loadCommandsFromPath($path . $item . '/', $namespace.'\\'.ucfirst($item))
                        );
                    }

                    if (is_file($path . $item)) {
                        $item = str_replace('.php', '', $item);
                        $classNamespace = $namespace.'\\'.ucfirst($item);
                        if (class_exists($classNamespace)) {
                            $commands[] = $classNamespace;
                        }                  
                    }
                }
            );
        return $commands;
    }

    private function loadCommandsFromAppPath($path)
    {
        $realPath = app_path($path);
        $commands = [];
        
        collect(scandir($realPath))
            ->each(
                function ($item) use ($path, $realPath) {
                    if (in_array($item, ['.', '..'])) { return;
                    }

                    if (is_dir($realPath . $item)) {
                        $this->loadCommandsFromAppPath($path . $item . '/');
                    }

                    if (is_file($realPath . $item)) {
                        $item = str_replace('.php', '', $item);
                        $class = str_replace('/', '\\', "Facilitador\\{$path}$item");

                        if (class_exists($class)) {
                            $commands[] = $class;
                        }                  
                    }
                }
            );
    }



    /**
     * Configs Paths
     */
    protected function getResourcesPath($folder)
    {
        return $this->getPackageFolder().'/../resources/'.$folder;
    }

    protected function getPublishesPath($folder)
    {
        return $this->getPackageFolder().'/../publishes/'.$folder;
    }

    protected function getDistPath($folder)
    {
        return $this->getPackageFolder().'/../dist/'.$folder;
    }

    private function getPackageFolder()
    {
        return FileExtractor::getFolderPathFromFile(ClasserExtractor::getFileFromClass($this));
    }


    /**
     * Load Alias and Providers
     */
    private function setProviders()
    {
        $this->setDependencesAlias();
        (new Collection(static::$providers))->map(
            function ($provider) {
                if (class_exists($provider)) {
                    $this->app->register($provider);
                }
            }
        );
    }
    private function setDependencesAlias()
    {
        $loader = AliasLoader::getInstance();
        (new Collection(static::$aliasProviders))->map(
            function ($class, $alias) use ($loader) {
                $loader->alias($alias, $class);
            }
        );
    }
}
