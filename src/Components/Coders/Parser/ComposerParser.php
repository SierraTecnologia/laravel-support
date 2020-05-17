<?php

declare(strict_types=1);


namespace Support\Components\Coders\Parser;

use Illuminate\Support\Str;

class ComposerParser
{

    /**
     * All of the discovered classes.
     *
     * @var array
     */
    protected $classes = [];

    /**
     * Create a new alias loader instance.
     *
     * @return void
     */
    public function __construct($excludedAliases = [], $ignorePackages = false)
    {
        $excludedAliases = collect($excludedAliases);
        $classMapPath = $this->getComposerFolder().'/composer/autoload_classmap.php';
        $vendorPath = dirname(dirname($classMapPath));

        $classes = include $classMapPath;
        foreach ($classes as $class => $path) {
            if (! Str::contains($class, '\\') || ($ignorePackages && Str::startsWith($path, $vendorPath))) {
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

    private function getComposerFolder()
    {

        // if (isset($_ENV['COMPOSER_VENDOR_DIR'])) {
        //     $classMapPath = $_ENV['COMPOSER_VENDOR_DIR'];
        // } else {
        //     $classMapPath = base_path().DIRECTORY_SEPARATOR.'vendor';
        // }
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $classMapPath = dirname(dirname($reflection->getFileName()));
        return $classMapPath;
    }
}