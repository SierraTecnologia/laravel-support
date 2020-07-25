<?php

namespace Support\Listeners;

use Support\Events\BreadDeleted;
use Support\Facades\Support;

class DeleteBreadMenuItem
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Delete a MenuItem for a given BREAD.
     *
     * @param BreadDeleted $bread
     *
     * @return void
     */
    public function handle(BreadDeleted $bread)
    {
        if (\Illuminate\Support\Facades\Config::get('sitec.facilitador.bread.add_menu_item')) {
            $menuItem = Support::model('MenuItem')->where('route', 'facilitador.'.$bread->dataType->slug.'.index');

            if ($menuItem->exists()) {
                $menuItem->delete();
            }
        }
    }
}
