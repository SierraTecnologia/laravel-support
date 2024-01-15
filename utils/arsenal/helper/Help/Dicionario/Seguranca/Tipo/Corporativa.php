<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Corporativa extends Tipo
{
    public static $name = 'Corporativa';
    public static $code = 'corporativa';

    /**
     * Quando maior menor a proteção
     *
     * @var integer
     */
    public static $nivel = 4;

    public static $nivelDescription = 'IV';

    public function __construct()
    {
        parent::__construct();
    }

}