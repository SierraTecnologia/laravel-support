<?php

declare(strict_types=1);

namespace Muleta\Traits\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
use Muleta\Utils\Extratores\ClasserExtractor;
use Muleta\Utils\Extratores\FileExtractor;
use Route;

trait ConsoleTools
{
    protected function getNamePackage($name)
    {
        $returnName = '';
        $names = explode('-', $name);
        foreach ($names as $newName) {
            $returnName .= ucfirst($newName);
        }
        return $returnName;
    }

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
            : $this->app->basePath('vendor'.DIRECTORY_SEPARATOR.''.$package);

        if (file_exists($path = $basePath.''.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations')) {
            $stubs = $this->app['files']->glob($path.''.DIRECTORY_SEPARATOR.'*.php.stub');
            $existing = $this->app['files']->glob($this->app->databasePath('migrations'.DIRECTORY_SEPARATOR.''.$package.''.DIRECTORY_SEPARATOR.'*.php'));

            $migrations = collect($stubs)->flatMap(
                function ($migration) use ($existing, $package) {
                    $sequence = mb_substr(basename($migration), 0, 2);
                    $match = collect($existing)->first(
                        function ($item, $key) use ($migration, $sequence) {
                            return mb_strpos($item, str_replace(['.stub', $sequence], '', basename($migration))) !== false;
                        }
                    );

                    return [$migration => $this->app->databasePath('migrations'.DIRECTORY_SEPARATOR.''.$package.''.DIRECTORY_SEPARATOR.''.($match ? basename($match) : date('Y_m_d_His', time() + $sequence).str_replace(['.stub', $sequence], '', basename($migration))))];
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
            : $this->app->basePath('vendor'.DIRECTORY_SEPARATOR.$package);

        if (file_exists($path = $basePath.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'config.php')) {
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
            : $this->app->basePath('vendor'.DIRECTORY_SEPARATOR.$package);

        if (file_exists($path = $basePath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views')) {
            $this->publishes([$path => $this->app->resourcePath('views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.$package)], $namespace.'-views');
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
            : $this->app->basePath('vendor'.DIRECTORY_SEPARATOR.$package);

        if (file_exists($path = $basePath.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'lang')) {
            $this->publishes([$path => $this->app->resourcePath('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.$package)], $namespace.'-lang');
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

        if (empty($folders)) {
            return ;
        }

        // Register artisan commands
        foreach ($folders as $key => $value) {
            if (file_exists($key) && is_dir($key)) {
                $commands = array_merge(
                    $commands,
                    $this->loadCommandsFromPath($key, $value)
                );
            }
        }
        $this->commands(array_values($commands));
    }
    
    protected function loadRoutesForRiCa($path)
    {
        if (!config('siravel.packagesRoutes', true)) {
            return ;
        }
        Route::group(['middleware' => ['web']], function () use ($path) {
            Route::group(
                [
                    'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers',
                    'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.main', ''),
                ],
                function ($router) use ($path) {
                    if (file_exists($path.DIRECTORY_SEPARATOR.'web.php')) {
                        include $path.DIRECTORY_SEPARATOR.'web.php';
                    } else {
                        $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'web');
                    }
                }
            );
            
            Route::group(['middleware' => 'auth'], function () use ($path) {
                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\User',
                        'middleware' => 'user',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.user', 'profile'),
                        'as' => 'profile.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'user.php')) {
                            include $path.DIRECTORY_SEPARATOR.'user.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'user');
                        }
                        if (file_exists($path.DIRECTORY_SEPARATOR.'profile.php')) {
                            include $path.DIRECTORY_SEPARATOR.'profile.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'profile');
                        }
                    }
                );
                
                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\Client',
                        'middleware' => 'client',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.client', 'client'),
                        'as' => 'client.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'client.php')) {
                            include $path.DIRECTORY_SEPARATOR.'client.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'client');
                        }
                    }
                );
                
                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\Painel',
                        'middleware' => 'painel',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.painel', 'painel'),
                        'as' => 'painel.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'painel.php')) {
                            include $path.DIRECTORY_SEPARATOR.'painel.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'painel');
                        }
                    }
                );

                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\Master',
                        'middleware' => 'master',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.master', 'master'),
                        'as' => 'master.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'master.php')) {
                            include $path.DIRECTORY_SEPARATOR.'master.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'master');
                        }
                    }
                );

                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\Admin',
                        'middleware' => 'admin',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.admin', 'admin'),
                        'as' => 'admin.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'admin.php')) {
                            include $path.DIRECTORY_SEPARATOR.'admin.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'admin');
                        }
                    }
                );
                
                Route::group(
                    [
                        'namespace' => '\\'.$this->getNamePackage($this->packageName).'\Http\Controllers\RiCa',
                        'middleware' => 'rica',
                        'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.rica', 'rica'),
                        'as' => 'rica.'.$this->packageName.'.',
                    ],
                    function ($router) use ($path) {
                        if (file_exists($path.DIRECTORY_SEPARATOR.'rica.php')) {
                            include $path.DIRECTORY_SEPARATOR.'rica.php';
                        } else {
                            $this->loadRoutesFromPath($path.DIRECTORY_SEPARATOR.'rica');
                        }
                    }
                );
            });
        });
    }

    /**
     * @param  string $path
     * @return $this
     */
    private function loadRoutesFromPath($path)
    {
        if (!file_exists($path = $path.DIRECTORY_SEPARATOR)) {
            return $this;
        }
        
        collect(scandir($path))
            ->each(
                function ($item) use ($path) {
                    if (in_array($item, ['.', '..'])) {
                        return;
                    }
                
                    if (is_dir($path . $item)) {
                        $commands = array_merge(
                            $commands,
                            $this->loadRoutesFromPath($path . $item)
                        );
                    }

                    if (is_file($path . $item)) {
                        include $path . $item;
                    }
                }
            );
        return $this;
    }

    /**
     * @param  string $path
     * @return $this
     */
    private function loadCommandsFromPath($path, $namespace)
    {
        $path = $path.DIRECTORY_SEPARATOR;
        $commands = [];
        
        if (!file_exists($path) && !is_dir($path)) {
            return false;
        }

        collect(scandir($path))
            ->each(
                function ($item) use ($path, $namespace, &$commands) {
                    if (in_array($item, ['.', '..'])) {
                        return;
                    }
                
                    if (is_dir($path . $item)) {
                        $commands = array_merge(
                            $commands,
                            $this->loadCommandsFromPath($path . $item, $namespace.'\\'.ucfirst($item))
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
                    if (in_array($item, ['.', '..'])) {
                        return;
                    }

                    if (is_dir($realPath . $item)) {
                        $this->loadCommandsFromAppPath($path . $item . DIRECTORY_SEPARATOR);
                    }

                    if (is_file($realPath . $item)) {
                        $item = str_replace('.php', '', $item);
                        $class = str_replace(DIRECTORY_SEPARATOR, '\\', "Facilitador\\{$path}$item");

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
        return $this->getPackageFolder().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.$folder;
    }

    protected function getPublishesPath($folder)
    {
        return $this->getPackageFolder().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'publishes'.DIRECTORY_SEPARATOR.$folder;
    }

    protected function getDistPath($folder)
    {
        return $this->getPackageFolder().DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'dist'.DIRECTORY_SEPARATOR.$folder;
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

    // @todo FAzer carregamento de seeders para packkages
    // protected function registerSeedsFrom($path)
    // {
    //     // foreach (glob("$path/*.php") as $filename)
    //     // {
    //     //     include $filename;
    //     //     $classes = get_declared_classes();
    //     //     $class = end($classes);

    //     //     $command = Request::server('argv', null);
    //     //     if (is_array($command)) {
    //     //         $command = implode(' ', $command);
    //     //         if ($command == "artisan db:seed") {
    //     //             Artisan::call('db:seed', ['--class' => $class]);
    //     //         }
    //     //     }

    //     // }
    // }
}
