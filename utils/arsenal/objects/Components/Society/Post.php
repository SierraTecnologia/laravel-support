<?php

namespace SiObjects\Components\Society;

use Log;
use MediaManager\Models\User;
use Integrations\Connectors\Instagram;


use MediaManager\Pipelines\Contracts\Registrator;
use MediaManager\Pipelines\Contracts\Notificator;

class Post implements Registrator, Notificator
{

    public $comments = [];

    public $likes = [];

    protected $post;
    
    public function getPost()
    {
        return $this->post;
    }
    public function __toString()
    {
        return 'this is object Post';
    }
    
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
    
    public function getComments()
    {
        return $this->comments;
    }
    
    public function getLikes()
    {
        return $this->likes;
        
    }
    
    public function getRelations()
    {
        
    }

}
