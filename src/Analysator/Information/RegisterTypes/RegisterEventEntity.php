<?php
/**
 * Algo que aconteceu, um evento, uma ação
 */

namespace Support\Analysator\Information\RegisterTypes;

class RegisterEventEntity extends AbstractRegisterType
{
    public static $name = 'Event';
    public $examples = [
        'event',
        'post',
        'calendar',
        'payment', 'pagamento', 'transferencia', 'transfer',



        'issue'
    ];


}
