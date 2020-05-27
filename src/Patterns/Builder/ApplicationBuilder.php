<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;
use Support\Components\Database\Schema\Table;
use Log;
use Support\Contracts\Output\OutputableTrait;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\ApplicationEntity;
use Illuminate\Database\Eloquent\Collection;
use Support\Elements\Entities\RelationshipEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;

class ApplicationBuilder extends BuilderAbstract
{
    public static $entityClasser = ApplicationEntity::class;



    public function prepare()
    {
        $this->systemEntity = \Support\Patterns\Builder\SystemBuilder::make('', $this->output)();
    }

    public function builder()
    {

        $this->entity->system = $this->systemEntity;
        $results = $this->systemEntity->models;
        $this->entity->relations = [];
        (new Collection($results))->map(
            function ($result) {
                $this->loadMapperBdRelations(
                    $result->getTableName(),
                    $result->toArray()['relations']
                );
            }
        );
        (new Collection($results))->map(
            function ($result) {
                $this->builderChildren($result);
            }
        );

        
    }

    public function builderChildren($result)
    {

        try {
            $this->entity->models[$result->getClassName()] = \Support\Patterns\Builder\EloquentBuilder::make(
                $this->entity,
                $this->output
            )($result->getClassName());
        } catch (EloquentTableNotExistException $th) {
            //@todo tabela nao existe verificar
        }
    }

    private function loadMapperBdRelations(string $tableName, $relations)
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
                    // StringModificator::singularizeAndLower($relation['name']).'_'.$relation['type'].'_'.StringModificator::singularizeAndLower($eloquentService->getTableName()),
                    // StringModificator::singularizeAndLower($relation['name'])
                    // $novoIndice
                );
            }
        }
    }


}
