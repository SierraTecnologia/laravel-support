<?php

namespace Support\Http\Controllers\Admin;

// Deps
use Artisan;
use App;
use Response;
use Support\Models\Application\Router;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Illuminate\Console\Application as ConsoleApplication;

// Run tasks from the admin
class Routers extends Base
{
    /**
     * @var int
     */
    const MAX_EXECUTION_TIME = 600; // How long to allow a command to run for

    /**
     * @var string
     */
    public $title = 'Routers';

    /**
     * @var string
     */
    public $description = "Trigger any command for this site.  Note: these may take awhile to execute.";

    /**
     * Populate protected properties on init
     */
    public function __construct()
    {
        $this->title = __('facilitador::routers.controller.title');
        $this->description = __('facilitador::routers.controller.description');

        parent::__construct();
    }

    /**
     * List all the tasks in the admin
     *
     * @return Response
     */
    public function index()
    {
        return $this->populateView(
            'support::tools.routers.index', [
            'routers' => Router::all(),
            ]
        );
    }
}
