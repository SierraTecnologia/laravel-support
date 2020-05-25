<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;

class SystemEntity extends EntityAbstract
{
    /**
     * indice = 'PrimaryKeys
     */
    public $mapperParentClasses;
    public $mapperTableToClasses;
    public $mapperClasserProcuracao;
    public $models = [];
    public $tables = [];



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
                $this->tables,
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
    public function returnTableForClass($className)
    {
        if (is_null($className) || empty($className)) {
            return false;
        }

        // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        if (!$find = \Support\Utils\Searchers\ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
            return false;
        }

        if (is_array($find)) {
            return $find[0];
        }
        return $find;
    }
    /**
     * Add uma classe rejeitada para ser trocada
     */
    public function loadMapperClasserProcuracao($eloquentEntity, $classForReplaced)
    {
        Log::channel('sitec-support')->debug(
            'Database Render (Rejeitando classe nao finais): Class: '.
            $classForReplaced
        );
        $this->mapperClasserProcuracao[$classForReplaced] = $eloquentEntity;
        $this->tempIgnoreClasses[] = $classForReplaced;
    }
    public function isForIgnoreClass($className)
    {
        if (is_null($className) || empty($className)) {
            return true;
        }

        if ($childrens = $this->haveChildren($className)) {
            foreach ($childrens as $children) {
                if (ClasserExtractor::getClassName($className) === ClasserExtractor::getClassName($children)) {
                    // @todo Verificar outras classes que nao possue nome igual mas é filha
                    /**
* ^ "Chieldren"
                    ^ "Finder\Models\Digital\Infra\Ci\Build"
                    ^ "build"
                    ^ "Finder\Models\Digital\Infra\Ci\Build\GitBuild"
                    ^ "gitbuild"
                    ^ array:4 [▼
                    0 => "Finder\Models\Digital\Infra\Ci\Build\GitBuild"
                    1 => "Finder\Models\Digital\Infra\Ci\Build\HgBuild"
                    2 => "Finder\Models\Digital\Infra\Ci\Build\LocalBuild"
                    3 => "Finder\Models\Digital\Infra\Ci\Build\SvnBuild"
                    ] 
*/

                    $this->loadMapperClasserProcuracao(
                        $children,
                        $className
                    );
                    return true;
                } 
                // else if(
                //     !in_array(
                //         ClasserExtractor::getClassName($children),
                //         ParseClass::$typesIgnoreName['model']
                //     )
                // ) {

                // }
            }
        }

        /**
         * Caso tenha um pai com nome diferente tbm ignora
         */
        if ($parent = $this->haveParent($className)) {
            if(!in_array(
                ClasserExtractor::getClassName($parent),
                ParseClass::$typesIgnoreName['model']
            ) && ClasserExtractor::getClassName($className) !== ClasserExtractor::getClassName($parent)
            ) {
                $this->loadMapperClasserProcuracao(
                    $parent,
                    $className
                );
                
                return true;
            }
        }

        return !$this->haveTableInDatabase($className);
    }



}
