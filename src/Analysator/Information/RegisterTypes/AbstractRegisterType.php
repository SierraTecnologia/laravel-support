<?php
/**
 * 
 */

namespace Support\Analysator\Information\RegisterTypes;

use Support\Contracts\Categorizador\AbstractCategorizador;

class AbstractRegisterType extends AbstractCategorizador
{
    /**
     * Identify
     */
    public static $typesByOrder = [
        RegisterOrganismEntity::class,
        RegisterEventEntity::class,
        RegisterHistoricEntity::class,
        RegisterTestimonialEntity::class,
        RegisterInformationEntity::class,
    ];

}
