<?php

namespace Support\Events;

use Illuminate\Queue\SerializesModels;
use Siravel\Models\Negocios\Menu;

class MenuDisplay
{
    use SerializesModels;

    public $menu;

    public function __construct(Menu $menu)
    {
        $this->menu = $menu;

        // @deprecate
        //
        event('facilitador.menu.display', $menu);
    }
}
