<?php

/**
 * Created by Cristian.
 * Date: 12/10/16 12:30 AM.
 */

namespace Support\Reliese\Database\Eloquent;

trait BlamableBehavior
{
    /**
     * Boot Blamable Behaviour trait for a model.
     */
    public static function bootBlamableBehavior()
    {
        static::observe(WhoDidIt::class);
    }
}
