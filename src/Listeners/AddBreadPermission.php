<?php

namespace Support\Listeners;

use Support\Events\BreadAdded;
use Support\Facades\Support;

class AddBreadPermission
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
     * Create Permission for a given BREAD.
     *
     * @param BreadAdded $event
     *
     * @return void
     */
    public function handle(BreadAdded $bread)
    {
        if (\Illuminate\Support\Facades\Config::get('sitec.facilitador.bread.add_permission') && file_exists(base_path('routes/web.php'))) {
            // Create permission
            //
            // Permission::generateFor(Str::snake($bread->dataType->slug));
            $role = Support::model('Role')->where('name', \Illuminate\Support\Facades\Config::get('sitec.facilitador.bread.default_role'))->firstOrFail();

            // Get permission for added table
            $permissions = Support::model('Permission')->where(['table_name' => $bread->dataType->name])->get()->pluck('id')->all();

            // Assign permission to admin
            $role->permissions()->attach($permissions);
        }
    }
}
