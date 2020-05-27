<?php

declare(strict_types=1);


namespace Support\Patterns\Parser;

use Illuminate\Support\Str;

class ComposerParser
{

    /**
     * All of the discovered classes.
     *
     * @var array
     */
    protected $classes = [];


    protected $composerFolder = false;
    protected $namespaces = false;

    /**
     * Create a new alias loader instance.
     *
     * @return void
     */
    public function __construct($excludedAliases = [], $ignorePackages = false)
    {
        $excludedAliases = collect($excludedAliases);
        $classMapPath = $this->getComposerFolder().'/composer/autoload_classmap.php';

        $classes = include $classMapPath;
        foreach ($classes as $class => $path) {
            if (! Str::contains($class, '\\') || ($ignorePackages && Str::startsWith($path, $this->getComposerFolder()))) {
                continue;
            }

            if (!$excludedAliases->filter(
                function ($alias) use ($class) {
                    return Str::startsWith($class, $alias);
                }
            )->isEmpty()
            ) {
                continue;
            }

            if (! isset($this->classes[$class])) {
                $this->classes[$class] = $path;
            }
        }
    }

    public function getClassFromPath($path)
    {
        foreach ($this->classes as $classer => $classPath) {
            if ($path === $classPath) {
                return $classer;
            }
        }
        return false;
    }

    public function getClasses()
    {
        return $this->classes;
    }

    public function getFilePathFromClass($className)
    {
        return $this->classes[$className];
    }
    public function getNamespaceFromClass($className)
    {
        return $this->getNamespaceFromFilePath(
            $this->getFilePathFromClass($className)
        );
    }
    public function getNamespaceFromFilePath($filePath)
    {
        foreach ($this->getNamespaces() as $namespace => $paths) {
            if (!is_array($paths)) {
                $paths = [$paths];
            }

            foreach ($paths as $path) {


                if (Str::startsWith($filePath, $path)) {
                    return $namespace;
                }
            }

        }
        return false;
    }
    public function getPathFromNamespace($namespace)
    {
        return $this->getNamespaces()[$namespace];
    }


    public function returnClassesByAlias($alias)
    {
        return collect($this->classes)->filter(
            function ($path, $class) use ($alias) {
                // if (!is_string($class)) dd($path, $class, $alias);
                // try {
                return Str::startsWith($class, $alias);

                // }catch(\Exception $e) {
                //     dd('Aqui', $path, $class, $alias);
                // }
            }
        );
    }

    protected function getComposerFolder()
    {
        if (!$this->composerFolder) {

            // if (isset($_ENV['COMPOSER_VENDOR_DIR'])) {
            //     $classMapPath = $_ENV['COMPOSER_VENDOR_DIR'];
            // } else {
            //     $classMapPath = base_path().DIRECTORY_SEPARATOR.'vendor';
            // }
            $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
            $this->composerFolder = dirname(dirname($reflection->getFileName()));
        }
        return $this->composerFolder;
    }
    protected function getNamespaces()
    {
        if (!$this->namespaces) {
            $this->namespaces = array_merge(
                include $this->getComposerFolder().'/composer/autoload_psr4.php',
                include $this->getComposerFolder().'/composer/autoload_namespaces.php'
            );

        }
        return $this->namespaces;

    }
}