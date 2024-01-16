<?php

namespace SiWeapons\Filesystem;

use Illuminate\Filesystem\Filesystem;

/**
 * User Helper - Provides access to logged in user information in views.
 *
 * @author Ricardo Sierra <ricardo@sierratecnologia.com>
 */
class Googledrive
{

    public function allFiles($directory, $hidden = false)
    {
            
        $path = "arquivos/";
        $diretorio = dir($path);
        
        echo "Lista de Arquivos do diretório '<strong>".$path."</strong>':<br />";
        while($arquivo = $diretorio -> read()){
            echo "<a href='".$path.$arquivo."'>".$arquivo."</a><br />";
        }
        $diretorio -> close();
    }
}
?>