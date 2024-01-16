<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Muleta\Analysator\Information\Group;


class GroupSocietyEntity extends EloquentGroup
{
    public static $name = 'Society';
    public static $order = 10;

    public $examples = [
        'data', // Tbm aparece em Audit mas esse vme primeiro


        'gender',
        'genero',
        'business','empresa', 'organization',


        'user', 'usuario', 'peaple', 'person',

        'location', 'country',


        'pircing', 'tatuagem', 'skill', 'habilidade', 'hability', 
    ];



}
