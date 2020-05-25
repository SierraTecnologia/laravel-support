<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

class DatabaseEntity
{
    public static $builder = '';

    /**
     * indice = 'PrimaryKeys
     */
    protected $tables;

    protected $dicionarioPrimaryKeys;

    protected $mapperTableToClasses;
}
