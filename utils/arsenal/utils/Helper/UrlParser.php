<?php
// @todo Repetido no Fabrica

namespace SiUtils\Helper;

use Illuminate\Support\Facades\Request;

class UrlParser
{

    public function __construct($url)
    {
        $this->parserUrl = parse_url($url);
    }

    public function getAttributes()
    {
        return [
            "scheme",
            "host",
            "port",
            "user",
            "pass",
            "path",
            "query",
            "fragment",
        ];
    }

    public function getPath()
    {
        return $this->parserUrl['path'];
    }

    public function getHost()
    {
        return $this->parserUrl['host'];
    }
}
