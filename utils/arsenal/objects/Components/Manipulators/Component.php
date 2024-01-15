<?php

namespace SiObjects\Components\Manipulators;

/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class Component
{

    protected $key = false;
    protected $category = false;
    protected $text = false;
    protected $loc = false;

    public function __construct($loc)
    {
        $this->loc = $loc;
    }
    
    
    public function getTarget()
    {
        return $this->target;
    }
}
?>