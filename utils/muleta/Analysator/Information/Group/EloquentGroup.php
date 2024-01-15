<?php
/**
 * Trata os Agrupamentos de Modelos
 */

namespace Muleta\Analysator\Information\Group;

use Muleta\Contracts\Categorizador\AbstractCategorizador;

class EloquentGroup extends AbstractCategorizador
{
    /**
     * Identify
     */
    public static $typesByOrder = [
        GroupSocietyEntity::class,
        GroupFinanceEntity::class,
        GroupBusinessEntity::class,
        GroupProjectEntity::class,
        GroupTeatroEntity::class,
        GroupBotsEntity::class,
        GroupMediaEntity::class,
        GroupAuditEntity::class,
    ];


}
