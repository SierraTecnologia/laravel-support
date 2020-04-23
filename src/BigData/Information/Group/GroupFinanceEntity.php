<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\BigData\HistoryType;


class GroupFinanceEntity extends EloquentGroup
{
    public $name = 'Finance';

    public static $examples = [
        'bank',
        'gasto',
        'renda'
    ];



}
