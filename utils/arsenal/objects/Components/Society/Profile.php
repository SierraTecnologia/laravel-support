<?php

namespace SiObjects\Components\Society;

use Log;
use MediaManager\Models\User;
use Integrations\Connectors\Instagram;

class Profile
{
    
    public function executeRoutines()
    {
        $this->getIntegrations();
    }
    
    
    public function getIntegrations()
    {
        return [
            new Instagram()
        ];
    }
    
    public function getPosts()
    {
        
    }
    
    public function getLikes()
    {
        
    }
    
    public function getRelations()
    {
        
    }

}
