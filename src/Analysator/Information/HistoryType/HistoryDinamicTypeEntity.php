<?php
/**
 * Informacoes que estao sempre mudando (Temperatura por exemplo)
 */

namespace Support\Analysator\Information\HistoryType;


class HistoryDinamicTypeEntity extends AbstractHistoryType
{
    public static $name = 'Dinamic';
    public static $order = 3;

    public $examples = [
        'temperature',
        'registro',
        'contador'
    ];



}
