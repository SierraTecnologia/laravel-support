<?php
/**
 * Uma informação em determinado Tempo (log)
 */

namespace Support\Analysator\Information\RegisterTypes;

class RegisterHistoricEntity extends AbstractRegisterType
{
    public static $name = 'Historic';
    public static $order = 600;

    public $examples = [
        'log', 'logger', 'registro', 'data'
    ];



}
