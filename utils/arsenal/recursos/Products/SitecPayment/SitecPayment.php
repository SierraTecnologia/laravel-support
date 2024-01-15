<?php

namespace Integrations\Connectors\SitecPayment;

use MediaManager\Models\Model;
use Log;
use MediaManager\Models\User;
use Integrations\Connectors\Integration;

class SitecPayment extends Integration
{
    public function getConnection($organizer = false)
    {
        $token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
        return new Pipedrive($token);
    }
}
