<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Muleta\Analysator\Information\Group;


class GroupTeatroEntity extends EloquentGroup
{
    public static $name = 'Teatro';
    public static $order = 90;

    public $examples = [
        'scene',
        'cena',
        'personagem',
    ];



}
