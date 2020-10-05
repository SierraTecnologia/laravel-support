<?php

namespace Support\Http\Controllers\Admin;

// Deps
use App;
use Artisan;
use Illuminate\Console\Application as ConsoleApplication;
use Illuminate\Http\Request;
use Response;
use Support\Models\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

// Run tasks from the admin
class Commands extends Base
{
    /**
     * @var int
     */
    const MAX_EXECUTION_TIME = 600; // How long to allow a command to run for

    /**
     * @var string
     */
    public $title = 'Commands';

    /**
     * @var string
     */
    public $description = "Trigger any command for this site.  Note: these may take awhile to execute.";

    /**
     * List all the tasks in the admin
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->populateView(
            'support::tools.commands.index',
            [
            'commands' => Command::all(),
            ]
        );
    }

    /**
     * Run one of the commands, designed to be called via AJAX
     *
     * @return Response
     */
    public function execute($command_name)
    {
        // Find it
        if (!($command = Command::find($command_name))) {
            App::abort(404);
        }

        // Run it, ignoring all output
        set_time_limit(self::MAX_EXECUTION_TIME);
        ob_start();
        Artisan::call($command->getName());
        ob_end_clean();

        // Return response
        return Response::json('ok');
    }

    /**
     * Populate protected properties on init
     */
    public function __construct()
    {
        $this->title = __('pedreiro::commands.controller.title');
        $this->description = __('pedreiro::commands.controller.description');

        parent::__construct();
    }
}
