<?php
/**
 * 
 */

namespace Support\BigData\\RegisterTypes;


abstract class AbstractRegisterType
{
    /**
     * Identify
     */
    protected $typesByOrder = [
        RegisterEventEntity::class,
        RegisterHistoricEntity::class,
        RegisterTestimonialEntity::class,
        RegisterInformationEntity::class,
        RegisterOrganismEntity::class,
    ];


    /**
     * Construct
     */
    public function __construct()
    {
        

    }

}
