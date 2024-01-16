<?php

namespace Integrations\Connectors\SitecBoss;

use Log;
use MediaManager\Models\User;

class Create extends SitecBoss
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
