<?php
/**
 * Informação Imutavel (Categorias, gostos, etc..)
 */
namespace Support\Analysator\Information\RegisterTypes;

class RegisterInformationEntity extends AbstractRegisterType
{
    public static $name = 'Information';

    public $examples = [
        'category', 'categoria', 'type', 'tipo',

        'gosto', 'skill',

        'role', 'grupo',

        'weapon'
    ];



}
