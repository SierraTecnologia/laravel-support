<?php

namespace SiObjects\Entities\Views;

/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class Directory
{

    protected $target = false;

    public function __construct($target)
    {
        $this->target = $target;
    }
    
    public function actions()
    {
        return [
            [
                'name' => 'open',
            ],
            [
                'name' => 'move',
            ],
            [
                'name' => 'delete',
            ],
            [
                'name' => 'new',
            ]
        ];
    }
}
?>