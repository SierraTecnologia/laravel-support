<?php
namespace Muleta\Helps;

use Illuminate\Contracts\Console\Kernel;

use Symfony\Component\Finder\Finder;
use Illuminate\Support\Collection;
use Muleta\Helps\DebugHelper;

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
                include $autoload;
                $vendorPath = dirname($autoload);
                $binariesPath = $vendorPath . '/bin/';
                break;
            }
        }
    }

    public function getMigrationsPaths(): array
    {
        return (new Collection($this->migrationsPaths))->map(
            function ($value) {
                return $value;
            }
        )->values()->all();
    }

    public function addMigrationsPaths($migrationsPaths): bool
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
     * @return void
     */
    public function runAll(): void
    {
        // if (!function_exists('config')) {
        //     function \Illuminate\Support\Facades\Config::get($address, $defaultValue) {
        //         return $defaultValue;
        //     }
        // }
        
        // $exitCode = (new Kernel)->call('migrate:refresh', [
        //     '--force' => true,
        // ]);


        $getAllFilesMigrations = $this->runMigrations();



    }



    /**
     * @return Finder|array
     *
     * @psalm-return Finder|array<empty, empty>
     */
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