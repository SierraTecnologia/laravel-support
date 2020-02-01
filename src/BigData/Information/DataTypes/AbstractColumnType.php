<?php
/**
 * Identifica as Tabelas e as Relaciona
 */

namespace Support\BigData\Information\DataTypes;


abstract class AbstractColumnType
{
    /**
     * Identify
     */
    protected $typesByOrder = [
        BdMoneyEntity::class,
        BdPorcentagemEntity::class,
        BdIntegerEntity::class,
        BdFloatEntity::class,
    ];


    /**
     * Construct
     */
    public function __construct()
    {
        

    }

}
