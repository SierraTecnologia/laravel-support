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
    public $mapperParentClasses = [];
    public $mapperTableToClasses = [];
    public $mapperClassNameToDataTypeReference = [];

    public function getReferenceForClass(string $className): string
    {
        if (isset($this->mapperClassNameToDataTypeReference[$className])) {
            return $this->mapperClassNameToDataTypeReference[$className];
        }
        return $className;
    }
}
