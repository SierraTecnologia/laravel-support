<?php
namespace Support\Helps;

use Illuminate\Contracts\Console\Kernel;

use Symfony\Component\Finder\Finder;
use Illuminate\Support\Collection;
use Support\Helps\DebugHelper;

/**
 * Array helper.
 */
class LoadLaravel
{
    protected $migrationsPaths = [
        __DIR__.'/../../vendor/sierratecnologia/informate/src/Migrations/',
        __DIR__.'/../../vendor/sierratecnologia/population/src/Migrations/',
        __DIR__.'/../../database/migrations/',
    ];

    public function __construct($migrationsPaths = [])
    {
        if (!empty($migrationsPaths)) {
            $this->migrationsPaths = $migrationsPaths;
        }

        /**
         * Load Require Files
         */
        $composerAutoload = [
            __DIR__ . '/../../../autoload.php',
            __DIR__ . '/../vendor/autoload.php',
        ];
        $vendorPath = $binariesPath = null;
        foreach ($composerAutoload as $autoload) {
            if (file_exists($autoload)) {
                require($autoload);
                $vendorPath = dirname($autoload);
                $binariesPath = $vendorPath . '/bin/';
                break;
            }
        }
    }

    public function getMigrationsPaths()
    {
        return (new Collection($this->migrationsPaths))->map(function($value) {
            return $value;
        })->values()->all();
    }

    public function addMigrationsPaths($migrationsPaths)
    {
        if (is_string($migrationsPaths)) {
            $migrationsPaths = [$migrationsPaths];
        }
        if (is_empty($migrationsPaths)) {
            return false;
        }


        $this->migrationsPaths = array_merge(
            $this->migrationsPaths,
            $migrationsPaths
        );

        return true;
    }

    /**
     * 
     */
    public function runAll()
    {
        // if (!function_exists('config')) {
        //     function config($address, $defaultValue) {
        //         return $defaultValue;
        //     }
        // }
        
        // $exitCode = (new Kernel)->call('migrate:refresh', [
        //     '--force' => true,
        // ]);


        $getAllFilesMigrations = $this->runMigrations();



    }



    public function runMigrations()
    {
        $finder = new Finder();
        $finder->in($this->getMigrationsPaths())->files()->sortByName();
        
        // check if there are any search results
        if (!$finder->hasResults()) {
            DebugHelper::warning('No Migrations: '.$path);

            return [];
        }

        foreach ($finder as $file) {
            include $file->getPathname();
            $fileName = explode('_', $file->getFilename());
            $className = '';
            foreach ($fileName as $partName) {
                if (!is_numeric($partName)) {
                    $className .= ucfirst($partName);
                }
            }
            $className = str_replace('.php', '', $className);
            $instanceClass = new $className;
            $instanceClass->up();
        }

        return $finder;
    }

}