<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Support\Traits\Debugger\DevDebug;
use Support\Traits\Debugger\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Elements\Entities\EloquentColumn;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;

use Support\Elements\Entities\EloquentEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;

use Support\Utils\Extratores\ArrayExtractor;

use Support\Patterns\Parser\ParseClass;
use Support\Patterns\Parser\ParseModelClass;
use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Inclusores\ArrayInclusor;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ClasserExtractor;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\SystemEntity;
use Support\Elements\Entities\RelationshipEntity;

class SystemBuilder extends BuilderAbstract
{
    public static $entityClasser = SystemEntity::class;

    public $renderDatabase;
    public $renderCoder;

    public function requeriments(): void
    {
        $this->renderDatabase = \Support\Patterns\Render\DatabaseRender::make('', $this->output)();
        $this->renderCoder = \Support\Patterns\Render\CodeRender::make('', $this->output)();
    }
    

    public function prepare(): void
    {
        $this->entity->tables = (new Collection($this->renderDatabase))->mapWithKeys(function (Table $table) {
            $primary = $this->returnRelationPrimaryKey($table);
            return [
                $primary => $table
            ];
        });
        // dd($this->entity->tables);
    }
    

    public function builder(): bool
    {


        $results = $this->renderCoder;
        $results = (new Collection($results))->reject(
            function ($result) {
                if (!$result) {
                    return true;
                }
                if ($result->hasError()) {
                    $this->entity->mergeErrors($result->getErrors());
                    return true;
                }
                return false;
            }
        );
        $results->map(
            function ($result) {
                $this->builderClasser($result);
            }
        );

        $results = $results->reject(
            function (ParseModelClass $result): bool {
                if (!$result->typeIs('model')) {
                    return true;
                }
                if (!$result->getTableName()) {
                    return true;
                }
                if ($this->entity->isForIgnoreClass($result->getClassName())) {
                    return true;
                }
                return false;
            }
        );

        /**
         * Grava referencia de tabelas para classes ja sem as classes com problema
         */
        $results->map(
            function (ParseModelClass $result): void {
                $this->loadMapperTableToClasses(
                    $result->getTableName(),
                    $result->getClassName()
                );
            }
        );

        // dd(
        //     $results["Informate\Models\Entytys\Fisicos\Weapon"]
        // );
        /**
         * Remove quem nao tem tabela no banco de dados e armazena os entitys
         */
        $results->reject(
            function (ParseModelClass $result): bool {
                return !$this->entity->haveTableInDatabase($result->getClassName());
            }
        )->map(
            function (ParseModelClass $result): void {
                $this->entity->models[$result->getClassName()] = $result;
            }
        );





        (new Collection($this->entity->models))->map(
            function (ParseModelClass $result): void {
                $this->builderAllRelations(
                    $result->getTableName(),
                    $result->toArray()['relations']
                );
            }
        );
        // dd(
        //     $this->entity
        // );

        return true;
    }

    // protected function renderData()
    // {
    //     $this->entity = new SystemEntity();
    // }
    protected function builderClasser($modelParser)
    {
        $this->registerMapperClassParents(
            $modelParser->getClassName(),
            $modelParser->getParentClassName()
        );
    }

    private function registerMapperClassParents($className, $classParent)
    {
        if (is_null($className) || empty($className) || is_null($classParent) || empty($classParent) || isset($this->mapperParentClasses[$className])) {
            return false;
        }

        // Ignora Oq nao serve
        if (in_array(
            ClasserExtractor::getClassName($classParent),
            ParseClass::$typesIgnoreName['model']
        )
        ) {
            return false;
        }

        $this->entity->mapperParentClasses[$className] = $classParent;
    }
    private function loadMapperTableToClasses(string $tableName, string $tableClass)
    {
        // Guarda Classe por Table
        if (isset($this->entity->mapperTableToClasses[$tableName])) {
            $this->entity->mapperTableToClasses = ArrayInclusor::setAndPreservingOldDataConvertingToArray(
                $this->entity->mapperTableToClasses,
                $tableName,
                $tableClass
            );

            // @todo Ignorar classes que uma extend a outra
            $this->entity->setError(
                'Duas classes para a mesma tabela: '.$tableName
            );
            return ;
        }
        $this->entity->mapperTableToClasses[$tableName] = $tableClass;
    }

    /**
     * Para Tabelas
     */

    private function returnRelationPrimaryKey(Table $table): string
    {
        if (!$primary = $this->returnPrimaryKeyFromIndexes($table->exportIndexesToArray())) {
            return $table->getName();
        }

        return StringModificator::singularizeAndLower($table->getName()).'_'.$primary;
    }

    private function returnPrimaryKeyFromIndexes(Array $indexes)
    {
        $primary = false;
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                if ($index['type'] == 'PRIMARY') {
                    return $index['columns'][0];
                }
            }
        }

        return $primary;
    }

    private function builderAllRelations(string $tableName, $relations): void
    {
        // Pega Relacoes
        if (empty($relations)) {
            return ;
        }

    
        foreach ($relations as $relation) {
            try {
                $tableTarget = $relation['name'];
                $tableOrigin = $tableName;

                $singulariRelationName = StringModificator::singularizeAndLower($relation['name']);
                $tableNameSingulari = StringModificator::singularizeAndLower($tableName);

                $type = $relation['type'];
                if (RelationshipEntity::isInvertedRelation($relation['type'])) {
                    $type = RelationshipEntity::getInvertedRelation($type);
                    $novoIndice = $tableNameSingulari.'_'.$type.'_'.$singulariRelationName;
                } else {
                    $temp = $tableOrigin;
                    $tableOrigin = $tableTarget;
                    $tableTarget = $temp;
                    $novoIndice = $singulariRelationName.'_'.$type.'_'.$tableNameSingulari;
                }
                if (!isset($this->entity->relations[$novoIndice])) {
                    $this->entity->relations[$novoIndice] = [
                        'code' => $novoIndice,
                        // Nome da Funcao
                        'name' => $tableTarget,
                        'table_origin' => $tableOrigin,
                        'table_target' => $tableTarget,
                        'pivot' => 0,
                        'type' => $type,
                        'relations' => []
                    ];
                }

                $this->entity->relations[$novoIndice]['relations'][] = $relation;

                /**
                 * Builder Relationship
                 */
                if (!isset($relation['origin_table_name']) || empty($relation['origin_table_name'])) {
                    $relation['origin_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
                        $relation['origin_table_class'],
                        $this->entity->models,
                        '[{{index}}]->getTableName()'
                    );
                }
                if (!isset($relation['related_table_name']) || empty($relation['related_table_name'])) {
                    $relation['related_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
                        $relation['related_table_class'],
                        $this->entity->models,
                        '[{{index}}]->getTableName()'
                    );
                }
                new RelationshipEntity($relation);

                // /**
                //  *  Agora pega só os morph
                //  */
                // if (strpos($relation['type'], 'Morph') !== false) {
                //     // /**
                //     //  * @todo quando tem pivod da merda
                //     //  */
                //     // if (isset($this->entity->relationsMorphs[$relation['foreignKey']])) {
                //     //     dd(
                //     //         $this->entity->system->tables['taskables'],
                //     //         $this->entity->relationsMorphs[$relation['foreignKey']],
                //     //         $relation
                //     //     );
                //     // }
                //     $this->entity->relationsMorphs[$relation['foreignKey']] = $relation; //['morph_type'];
                //     // echo 'true';
                // }
                



                // @todo Debugar aqui
                // if (count($this->entity->relations[$novoIndice]['relations'])>1) {
                //     dd(
                //         $novoIndice,
                //         $this->entity->relations[$novoIndice]['relations'],
                //         'AplicatipBuilderRElatiosm'
                //     );
                // }
            } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                $reference = false;
                if (isset($classUniversal) && !empty($classUniversal) && is_string($classUniversal)) {
                    $reference = [
                        'model' => $classUniversal
                    ];
                } 
                $this->setErrors(
                    $e,
                    $reference
                );
                // } catch (\Exception $e) {
                dd(
                    'LaravelSupport>Database>> Não era pra Cair Erro aqui',
                    $e,
                    $relation,
                    $relation['name'],
                    $relation['type']
                );
            }
        }

    }
}
