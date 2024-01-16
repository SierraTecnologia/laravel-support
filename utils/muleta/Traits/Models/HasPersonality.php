<?php

namespace Muleta\Traits\Models;

use Log;

trait HasPersonality
{
    
    /**
     * Many To Many (Polymorphic)
     */
    public function gostos()
    {
        return $this->morphToMany('Informate\Models\Entytys\About\Gosto', 'gostoable');
    }
    public function skills()
    {
        return $this->morphToMany('Informate\Models\Entytys\About\Skill', 'skillable');
    }
    public function itens()
    {
        return $this->morphToMany('Informate\Models\Entytys\Fisicos\Item', 'itemable');
    }
    
    /**
     * Get all of the post's weapons.
     */
    public function ferramentas()
    {
        return $this->weapons();
    }
    public function weapons()
    {
        return $this->morphToMany('Informate\Models\Entytys\Fisicos\Weapon', 'weaponable');
    }
    /**
     * Get all of the post's equipaments.
     */
    public function equipaments()
    {
        return $this->morphToMany('Informate\Models\Entytys\Fisicos\Equipament', 'equipamentable');
    }


    /**
     * Events
     */
    public static function bootHasPersonality()                                                                                                                                                             
    {

        // static::deleting(function (self $user) {
        //     optional($user->photos)->each(function (Photo $photo) {
        //         $photo->delete();
        //     });
        // });
    }
    

}
