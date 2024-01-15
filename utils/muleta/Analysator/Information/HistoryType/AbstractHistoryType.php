<?php
/**
 * 
 */

namespace Muleta\Analysator\Information\HistoryType;

use Muleta\Contracts\Categorizador\AbstractCategorizador;

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
