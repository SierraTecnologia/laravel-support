<?php
/**
 * Baseado no Modelo System
 */

namespace Support\Patterns\Entity;

use Support\Contracts\Manager\EntityAbstract;
use Muleta\Utils\Extratores\ClasserExtractor;
use Support\Patterns\Parser\ParseClass;
use Muleta\Traits\Debugger\HasErrors;
use Support\Patterns\Parser\ParseModelClass;
use Pedreiro\Exceptions\Coder\EloquentTableNotExistException;
use Muleta\Utils\Searchers\ArraySearcher;
use Support\Components\Errors\TableNotExistError;
use Support\Components\Database\Schema\Table;

class SystemEntity extends EntityAbstract
{
    use HasErrors;
    
    public $models = [];
    public $tables = [];

    /**
     * indice = 'PrimaryKeys
     */
    public $mapperParentClasses;
    public $mapperTableToClasses;
    public $mapperClassNameToDataTypeReference;

    public $relations = [];
    public $relationsMorphs = [];

    public static $mapper = [
        'mapperParentClasses',
        'mapperTableToClasses',
        'mapperClassNameToDataTypeReference',
    ];


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

        return ArraySearcher::arrayIsearch($className, $this->mapperParentClasses);
    }
    public function haveTableInDatabase($className): bool
    {
        if (is_null($className) || empty($className)) {
            return false;
        }
        
        // // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        // if (ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
        //     return true;
        // }
        if ($tableName = $this->returnTableForClass($className)) {
            if (ArraySearcher::arraySearchByAttribute(
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
            
            $this->setError(
                TableNotExistError::make(
                    $tableName,
                    [
                        'file' => $className
                    ]
                )
            );
        }
        return false;
    }
    /**
     * Add uma classe rejeitada para ser trocada
     */
    public function loadMapperClasserProcuracao(string $eloquentEntity, string $classForReplaced): void
    {
        // Log::channel('sitec-support')->debug(
        //     'Database Render (Rejeitando classe nao finais): Class: '.
        //     $classForReplaced
        // );
        $this->mapperClassNameToDataTypeReference[$classForReplaced] = $eloquentEntity;
    }

    /**
     * Acho que deve ta no builder @todo
     */
    public function isForIgnoreClass(string $className): bool
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
                    ^ "Fabrica\Models\Infra\Ci\Build"
                    ^ "build"
                    ^ "Fabrica\Models\Infra\Ci\Build\GitBuild"
                    ^ "gitbuild"
                    ^ array:4 [▼
                    0 => "Fabrica\Models\Infra\Ci\Build\GitBuild"
                    1 => "Fabrica\Models\Infra\Ci\Build\HgBuild"
                    2 => "Fabrica\Models\Infra\Ci\Build\LocalBuild"
                    3 => "Fabrica\Models\Infra\Ci\Build\SvnBuild"
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

        return false;
    }

    public function returnTableForClass($className)
    {
        if (is_null($className) || empty($className)) {
            return false;
        }

        // Nao funciona pois todas as tabelas (mesmo nao existentes estao aqui)
        if (!$find = ArraySearcher::arrayIsearch($className, $this->mapperTableToClasses)) {
            return false;
        }

        if (is_array($find)) {
            return $find[0];
        }
        return $find;
    }

    public function returnClassForTableName(string $tableName): string
    {
        if (is_array($modelClass = $this->mapperTableToClasses[$tableName])) {
            $modelClass = $modelClass[0];
        }
        return $modelClass;
    }

    public function returnTableForName(string $tableName): Table
    {
        $databaseTableObject = false;
        if ($foundTableRender = ArraySearcher::arraySearchByAttribute(
            $tableName,
            $this->tables,
            'name'
        )
        ) {
            $databaseTableObject = $this->tables[$foundTableRender[0]];
        }
        if (!$databaseTableObject) {
            throw new EloquentTableNotExistException($this->entity->code, $tableName);
        }
        return $databaseTableObject;
    }

}
