<?php
/**
 * 
 */

namespace Support\BigData\\HistoryType;


abstract class AbstractHistoryType
{
    /**
     * Identify
     */
    protected $typesByOrder = [
        HistoryDinamicTypeEntity::class,
        HistoryImutavelTypeEntity::class,
        HistoryProgressTypeEntity::class,
    ];


    /**
     * Construct
     */
    public function __construct()
    {
        

    }

}
