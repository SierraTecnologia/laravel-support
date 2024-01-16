<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Publica extends Tipo
{
    public static $name = 'Secreta';
    public static $code = 'secreta';

    /**
     * Quando maior menor a proteção
     *
     * @var integer
     */
    public static $nivel = 5;

    public static $nivelDescription = 'V';

    public function __construct()
    {
        parent::__construct();
    }

}