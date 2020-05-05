<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\BigData\HistoryType;


class GroupSocietyEntity extends EloquentGroup
{
    public $name = 'Society';

    public static $examples = [
        'gender',
        'genero',
        'business'
    ];



}
