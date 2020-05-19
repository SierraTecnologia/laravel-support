<?php
/**
 * 
 */

namespace Support\Analysator\Information\HistoryType;

use Support\Contracts\Categorizador\AbstractCategorizador;

class AbstractHistoryType extends AbstractCategorizador
{
    /**
     * Identify
     */
    public static $typesByOrder = [
        HistoryDinamicTypeEntity::class,
        HistoryImutavelTypeEntity::class,
        HistoryProgressTypeEntity::class,
    ];

}
