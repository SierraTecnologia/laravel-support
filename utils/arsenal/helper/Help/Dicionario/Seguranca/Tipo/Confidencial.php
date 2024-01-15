<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Confidencial extends Tipo
{
    public static $name = 'Confidencial';
    public static $code = 'confidencial';

    /**
     * Quando maior menor a proteção
     *
     * @var integer
     */
    public static $nivel = 2;

    public static $nivelDescription = 'II';

    public function __construct()
    {
        parent::__construct();
    }

}