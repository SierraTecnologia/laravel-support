<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupFinanceEntity extends EloquentGroup
{
    public static $name = 'Finance';
    public static $order = 20;

    public $examples = [
        'bank',
        'gasto',
        'renda',



        'spent', 'saldo',
    ];



}
