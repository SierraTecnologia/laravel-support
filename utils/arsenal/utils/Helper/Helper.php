<?php
// @todo Repetido no Fabrica

namespace SiUtils\Helper;

class Helper
{
    public static function removeTags($string, $tags)
    {
        $tagsString = "";
        foreach($tags as $key => $v) {
            $tagsString .= $key == count($tags)-1 ? $v : "{$v}|";
        }

        $patterns = array("/(<\s*\b({$tagsString})\b[^>]*>)/i", "/(<\/\s*\b({$tagsString})\b\s*>)/i");
        $output = preg_replace($patterns, "", $string);

        return $output;
    }
}
