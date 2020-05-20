<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupBotsEntity extends EloquentGroup
{
    public static $name = 'Bots';
    public static $order = 80;

    public $examples = [
        'worker',
        'action',
        'ocorrence',

        'routine',

        'stage',

        'resolution'
    ];



}

