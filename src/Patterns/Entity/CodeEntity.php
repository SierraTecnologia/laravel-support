<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;

class CodeEntity extends EntityAbstract
{
    /**
     * indice = 'PrimaryKeys
     */
    public $mapperParentClasses;



    public function haveParent($classChild)
    {
        if (is_null($classChild) || empty($classChild) || !isset($this->mapperParentClasses[$classChild])) {
            return false;
        }

        return $this->mapperParentClasses[$classChild];
    }
    public function haveChildren($className)
    {
        if (is_null($className) || empty($className) || !in_array($className, $this->mapperParentClasses)) {
            return false;
        }

        return \Support\Utils\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperParentClasses);
    }
    public function haveTableInDatabase($className)
    {
        if (is_null($className) || empty($className)) {
            return false;
        }
        
        // // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        // if (\Support\Utils\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
        //     return true;
        // }
        if ($tableName = $this->returnTableForClass($className)) {
            if (\Support\Utils\Searchers\ArraySearcher::arraySearchByAttribute(
                $tableName,
                $this->displayTables,
                'name'
            )
            ) {
                return true;
            }

            if (!is_string($tableName) || empty($tableName)) {
                dd(
                    'Nao era pra ta aqui Cararin',
                    $tableName
                );
            }
            
            $error = \Support\Components\Errors\TableNotExistError::make(
                $tableName,
                [
                    'file' => $className
                ]
            );
            $this->setError(
                $error
            );
            $this->tempErrorClasses[$className] = $error->getDescription();
        }
        return false;
    }

}
