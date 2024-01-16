<?php
/**
 * 
 */

namespace Muleta\Analysator\Information\RegisterTypes;

use Muleta\Contracts\Categorizador\AbstractCategorizador;

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
