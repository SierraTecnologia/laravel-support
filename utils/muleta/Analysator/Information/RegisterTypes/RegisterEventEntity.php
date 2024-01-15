<?php
/**
 * Algo que aconteceu, um evento, uma ação
 */

namespace Muleta\Analysator\Information\RegisterTypes;

class RegisterEventEntity extends AbstractRegisterType
{
    public static $name = 'Event';
    public static $order = 300;
    public $examples = [
        'event',
        'post',
        'calendar',
        'payment', 'pagamento', 'transferencia', 'transfer',



        'issue'
    ];


}
