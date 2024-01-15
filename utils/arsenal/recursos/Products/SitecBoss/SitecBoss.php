<?php

namespace SiWeapons\Products\SitecBoss;

use MediaManager\Models\Model;
use Log;
use MediaManager\Models\User;
use Integrations\Connectors\Integration;

class SitecBoss extends Integration
{
    public function getConnection($organizer = false)
    {
        return true;
    }
}
