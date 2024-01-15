<?php

namespace Integrations\Connectors\SitecPayment;

use Log;
use MediaManager\Models\User;

class Create extends SitecPayment
{
    public function project(Project $project)
    {
        $arrayFromProject = array(
            'description' => $project->description,
            'issues_enabled' => false
        );
        $project = $client->api('projects')->create($project->name, $arrayFromProject);
    }
}
