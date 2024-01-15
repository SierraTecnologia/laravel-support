<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Muleta\Analysator\Information\Group;


class GroupProjectEntity extends EloquentGroup
{
    public static $name = 'Project';
    public static $order = 20;

    public $examples = [
        'build',
        'commit',
        'release'
    ];



}
