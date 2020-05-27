<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;

class ApplicationEntity extends EntityAbstract
{
    /**
     * indice = 'PrimaryKeys
     */
    public $system;
    public $models = [];
    public $relations = [];


}
