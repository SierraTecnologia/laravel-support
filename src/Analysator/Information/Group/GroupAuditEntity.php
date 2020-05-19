<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\Group;


class GroupAuditEntity extends EloquentGroup
{
    public static $name = 'Audit';

    public $examples = [
        'log', 'logger', 'registro', 'data'
    ];



}
