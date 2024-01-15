<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Secreta extends Tipo
{
    public static $name = 'Secreta';
    public static $code = 'secreta';

    /**
     * Quando maior menor a proteção
     *
     * @var integer
     */
    public static $nivel = 1;

    public static $nivelDescription = 'I';

    public function __construct()
    {
        parent::__construct();
    }

}