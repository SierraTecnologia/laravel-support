<?php

namespace SiObjects\Components\Society;

use Log;
use MediaManager\Models\User;
use Integrations\Connectors\Instagram;

use MediaManager\Pipelines\Contracts\Registrator;
use MediaManager\Pipelines\Contracts\Notificator;

class Pipeline
{

    protected $pipeline;

    public function getPipeline()
    {
        return $this->pipeline;
    }
    public function __toString()
    {
        return 'this is object Pipeline';
    }
}