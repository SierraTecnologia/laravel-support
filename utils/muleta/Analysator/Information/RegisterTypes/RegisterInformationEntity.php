<?php
/**
 * Informação Imutavel (Categorias, gostos, etc..)
 */
namespace Muleta\Analysator\Information\RegisterTypes;

class RegisterInformationEntity extends AbstractRegisterType
{
    public static $name = 'Information';
    public static $order = 500;

    public $examples = [
        'category', 'categoria', 'type', 'tipo',

        'gosto', 'skill',

        'role', 'grupo',

        'weapon',
        'acessorio'
    ];



}
