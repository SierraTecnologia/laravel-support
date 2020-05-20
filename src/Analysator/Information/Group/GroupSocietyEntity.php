<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupSocietyEntity extends EloquentGroup
{
    public static $name = 'Society';
    public static $order = 10;

    public $examples = [
        'gender',
        'genero',
        'business','empresa',


        'user', 'usuario',

        'location', 'country',


        'pircing', 'tatuagem', 'skill', 'habilidade', 'hability', 
    ];



}
