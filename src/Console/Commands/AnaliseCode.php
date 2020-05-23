<?php

namespace Facilitador\Console\Commands\Manutencao;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use Log;
use Facilitador\Services\DiscoverService;

class AnaliseCode extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'siravel:facilitador:analise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin. Make sure there is a user with the admin role that has all of the necessary permissions.';

    /**
     * Get user options.
     */
    protected function getOptions()
    {
        return [
            ['create', null, InputOption::VALUE_NONE, 'Create an admin user', null],
        ];
    }
    public function fire()
    {
        return $this->handle();
    }

    /**
     * Create the new admin with input from the user
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $discoverService = new DiscoverService;

        dd('Aqui Facilitador');//,
        // $discoverService);

        

        $this->info('The user now has full access to your site.');
    }

    /**
     * Get command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['email', InputOption::VALUE_REQUIRED, 'The email of the user.', null],
        ];
    }
}