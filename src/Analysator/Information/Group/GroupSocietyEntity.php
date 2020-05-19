<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupSocietyEntity extends EloquentGroup
{
    public static $name = 'Society';

    public static $examples = [
        'gender',
        'genero',
        'business'
    ];



}
