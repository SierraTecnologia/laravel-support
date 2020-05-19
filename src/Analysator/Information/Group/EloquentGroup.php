<?php
/**
 * Trata os Agrupamentos de Modelos
 */

namespace Support\Analysator\Information\Group;

use Support\Contracts\Categorizador\AbstractCategorizador;

class EloquentGroup extends AbstractCategorizador
{
    /**
     * Identify
     */
    public static $typesByOrder = [
        GroupFinanceEntity::class,
        GroupSocietyEntity::class,
        GroupAuditEntity::class,
    ];


}
