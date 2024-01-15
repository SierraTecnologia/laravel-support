<?php

namespace SiHelper\Help\Dicionario\Seguranca\Tipo;

class Reservada extends Tipo
{
    public static $name = 'Reservada';
    public static $code = 'reservada';

    /**
     * Quando maior menor a proteção
     *
     * @var integer
     */
    public static $nivel = 3;

    public static $nivelDescription = 'III';

    public function __construct()
    {
        parent::__construct();
    }

}