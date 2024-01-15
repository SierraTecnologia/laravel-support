<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Muleta\Analysator\Information\Group;


class GroupBusinessEntity extends EloquentGroup
{
    public static $name = 'Business';
    public static $order = 94;

    public $examples = [
        'website',
        'dominio',
        'url',
        'link',
        'rrs',
        'sitio',

        'wiki',
        'page',


        'treinner'
    ];



}
