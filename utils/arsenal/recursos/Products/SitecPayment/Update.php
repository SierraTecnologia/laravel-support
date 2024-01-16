<?php

namespace Integrations\Connectors\SitecPayment;

use Log;
use MediaManager\Models\User;

class Update extends SitecPayment
{
    public function organization(Organization $organization)
    {
        // Also simple to update any SitecPayment resource value
        $organization = $this->_connection->organizations->update(
            1,
            [
                'name' => 'Big Code'
            ]
        );
        var_dump($organization->getData());
    }
}
