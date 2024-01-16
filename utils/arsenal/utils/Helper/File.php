<?php
// @todo Repetido no Fabrica

namespace SiUtils\Helper;

use Integrations\Tools\Config;

/**
 * Helper class for dealing with SSH keys.
 */
class File
{
    public static function createFile($dir, $contents)
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';
        foreach($parts as $part) {
            if (!empty($part)) {
                if(!is_dir($dir .= "/$part")) {
                    mkdir($dir);
                }
            }
        }
        file_put_contents("$dir/$file", $contents);
    }
}