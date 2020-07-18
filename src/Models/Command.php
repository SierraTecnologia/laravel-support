<?php

namespace Support\Models;

use App;
use Support\Patterns\Parser\ComposerParser;

/**
 * Adds some shared functionality to taks as well as informs the Facilitador
 * admin interface.  Also functions as a sort of model.
 */
class Command
{

    /**
     * Admins should not be localized
     *
     * @var boolean
     */
    public static $localizable = false;
    /**
     * @var int
     */
    const MAX_EXECUTION_TIME = 600; // How long to allow a command to run for

    //---------------------------------------------------------------------------
    // Queries
    //---------------------------------------------------------------------------

    /**
     * Scan commands directory for custom commands
     *
     * @return array
     */
    public static function all()
    {
        // Add custom ones
        $commands = [];

        // Add Laravel ones
        App::register('Illuminate\Foundation\Providers\ConsoleSupportServiceProvider'); // Needed for compile and optimize
        $commands['Laravel']['Migrate'] = App::make('command.migrate');
        $commands['Laravel']['Seed'] = App::make('command.seed');
        $commands['Laravel']['Cache clear'] = App::make('command.cache.clear');
        $commands['Laravel']['Clear compiled classes'] = App::make('command.clear-compiled');

        /**
         * App Commands
         */
        $commands = self::allCustom($commands);

        /**
         * Commands from Config
         */
        $commandFolders = config('housekeepers.components.commandsFolders');
        if (is_array($commandFolders)) {
            foreach ($commandFolders as $folder) {
                $news = self::allCustom($commands, $folder);
                foreach ($news as $indixe=>$new) {
                    if (!isset($commands[$indixe])) {
                        $commands[$indixe] = $new;
                    } else {
                        $commands[$indixe] = array_merge($commands[$indixe], $new);
                    }
                }
            }
        }
        // dd($commands);
        // Return matching commands
        return $commands;
    }

    /**
     * Scan commands directory for custom commands
     *
     * @return array
     */
    public static function allCustom($commands = [], $pathForLoadCommands = false)
    {

        // Loop through PHP files
        if (!$pathForLoadCommands) {
            $dir = app_path('Console/Commands');
        } else {
            $dir = base_path($pathForLoadCommands);
        }

        // Return
        return self::searchForCommands($commands, $dir);
    }

    // Get a specific command
    // @param $command i.e. "Seed", "FeedCommand"
    public static function searchForCommands($commands, $dir)
    {
        $composer = resolve(ComposerParser::class);
        if (!is_dir($dir)) { 
            return $commands;
        }

        $files = scandir($dir);
        foreach ($files as $file) {
            if (in_array($file, ['.', '..'])) {
                continue;
            }
            $path = $dir.'/'.$file;
            if (is_dir($path)) {
                $commands = self::searchForCommands($commands, $path);
            }
            if (!preg_match('#\w+\.php#', $file)) {
                continue;
            }

            // Build an instance of a command using the service container
            $class = $composer->getClassFromPath($path);
            // $class = $composer->getNamespaceFromFilePath($dir).'Console\Commands\\'.basename($path, '.php');
            $command = app($class);

            // Validate command
            if (!is_a($command, 'Illuminate\Console\Command')) {
                continue;
            }

            // Get namespace
            $name = $command->getName();
            if (strpos($name, ':')) {
                $explode = explode(':', $name);
                $name = array_pop($explode);
                $namespace = implode(' ', $explode);
            } else {
                $namespace = 'misc';
            }

            // Massage name
            $name = str_replace('-', ' ', ucfirst($name));

            // Group commands by namespace
            $namespace = ucfirst($namespace);
            $name = ucfirst($name);
            if (!array_key_exists($namespace, $commands)) {
                $commands[$namespace] = [];
            }
            $commands[$namespace][$name] = $command;
        }
        return $commands;
    }

    // Get a specific command
    // @param $command i.e. "Seed", "FeedCommand"
    public static function find($search_command)
    {
        // Get all the commands
        $commands = self::all();

        // Loop through them to find the passed one
        foreach ($commands as $subcommands) {
            foreach ($subcommands as $command) {
                if ($search_command == $command->getName()) {
                    return $command;
                }
            }
        }

        return false;
    }
}
