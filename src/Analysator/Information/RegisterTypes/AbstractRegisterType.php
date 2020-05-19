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
        RegisterEventEntity::class,
        RegisterHistoricEntity::class,
        RegisterTestimonialEntity::class,
        RegisterInformationEntity::class,
        RegisterOrganismEntity::class,
    ];

}
