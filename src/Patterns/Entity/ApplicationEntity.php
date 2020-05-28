<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;

class ApplicationEntity extends EntityAbstract
{

    public static $mapper = [
        'mapperParentClasses',
        'mapperTableToClasses',
        'mapperClassNameToDataTypeReference',
    ];

    /**
     * indice = 'PrimaryKeys
     */
    public $system;
    public $models = [];
    public $relations = [];
    public $relationsMorphs = [];
    public $mapperParentClasses = [];
    public $mapperTableToClasses = [];
    public $mapperClassNameToDataTypeReference = [];

    public function getReferenceForClass($className)
    {
        if (isset($this->mapperClassNameToDataTypeReference[$className])) {
            return $this->mapperClassNameToDataTypeReference[$className];
        }
        return $className;
    }
}
