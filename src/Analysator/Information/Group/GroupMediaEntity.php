<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupMediaEntity extends EloquentGroup
{
    public static $name = 'Media';
    public static $order = 95;

    public $examples = [
        'photo',
        'foto',
        'video',
        'album',
        'thumbnail'
    ];



}
