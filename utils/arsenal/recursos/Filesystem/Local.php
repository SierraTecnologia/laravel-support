<?php

namespace SiWeapons\Filesystem;

use Illuminate\Filesystem\Filesystem;

/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class Local extends Filesystem
{

    public function allFiles($directory, $hidden = false)
    {
        $arquivos = parent::allFiles($directory->getTarget());
        $return = [];
        foreach( $arquivos as  $arquivo){
            $return[] = new File($arquivo);
        }
        return $return;
    }

    public function get($path, $lock = false)
    {
        return parent::get($path->getTarget());
    }
}
?>