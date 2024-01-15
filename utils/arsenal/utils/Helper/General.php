<?php
// @todo Repetido no Fabrica

namespace SiUtils\Helper;

use Illuminate\Support\Facades\Request;

class General
{

    public function __construct()
    {
        
    }

    public static function getIp()
    {
        return Request::ip();
    }

    public static function generateToken()
    {
        // Name of selected hashing algorithm (e.g. "md5", "sha256", "haval160,4", etc..) 
        return hash('sha256', uniqid(rand(), true));
    }

    public static function getSlugForUrl($url)
    {
        $replaces = [
            '.com.br',
            '.com',
            '.br',
            '.www',
            '.org',
            '.net',
            '.pt',
            '.edu',
            '.info',
            'https://',
            'http://',
        ];
        $withSubDomain = explode(":", str_replace($replaces, "", $url))[0];
        return explode(".", $withSubDomain)[0];
    }
}
