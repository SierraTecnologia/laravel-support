<?php

namespace SiWeapons\Products\SitecBoss;

use Log;
use MediaManager\Models\User;

class Update extends SitecBoss
{
    public function organization(Organization $organization)
    {
        // Also simple to update any SitecBoss resource value
        $organization = $this->_connection->organizations->update(
            1,
            [
                'name' => 'Big Code'
            ]
        );
        var_dump($organization->getData());
    }
}
