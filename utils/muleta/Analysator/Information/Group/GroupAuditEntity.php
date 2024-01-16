<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Muleta\Analysator\Information\Group;


class GroupAuditEntity extends EloquentGroup
{
    public static $name = 'Audit';
    public static $order = 100;

    public $examples = [
        'log', 'logger', 'registro', 'data'
    ];



}
