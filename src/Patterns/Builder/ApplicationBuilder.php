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
use Support\Models\Application\DataRow;
use Support\Models\Application\DataType;

class ApplicationBuilder extends BuilderAbstract
{
    public static $entityClasser = ApplicationEntity::class;

    public $systemEntity;

    public function requeriments()
    {
        $this->systemEntity = \Support\Patterns\Builder\SystemBuilder::make('', $this->output)();
    }

    public function prepare()
    {
        $this->entity->system = $this->systemEntity;
        
        $this->entity->mapperParentClasses = $this->systemEntity->mapperParentClasses;
        $this->entity->mapperTableToClasses = $this->systemEntity->mapperTableToClasses;
        $this->entity->mapperClassNameToDataTypeReference = $this->systemEntity->mapperClassNameToDataTypeReference;
    }

    public function builder()
    {
        $results = $this->entity->system->models;

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

        // dd(
        //     $this->entity->models["Informate\Models\Entytys\Fisicos\Weapon"]
        // );
        return true;
        
    }

    public function builderChildren($result)
    {

        try {
            $this->entity->models[$result->getClassName()] = \Support\Patterns\Builder\EloquentBuilder::make(
                $this->entity,
                $this->output
            )($result->getClassName());

            // Register DataType/DataRow
            $this->registerDataType($this->entity->models[$result->getClassName()]);
        } catch (EloquentTableNotExistException $th) {
            dd(
                $th
            );
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
                );
            }
        }
    }


    public function registerDataType($result)
    {


        $modelDataType = $this->dataTypeForCode($result->code);
        if (!$modelDataType->exists) {
            $this->info("Criando DataType: ".$result->code);
            // Name e Slug sao unicos
            $modelDataType->fill(
                [
                'name'                  => $result->code, //strtolower($result->getName(true)),
                'slug'                  => $result->code, //strtolower($result->getName(true)),
                'display_name_singular' => $result->getName(),
                'display_name_plural'   => $result->getName(),
                'icon'                  => $result->getIcon(),
                'model_name'            => $result->code,
                'controller'            => '',
                'generate_permissions'  => 1,
                'description'           => '',
                'table_name'              => $result->getTablename(),
                'key_name'                => $result->getData('getKeyName'),
                'key_type'                => $result->getData('getKeyType'),
                'foreign_key'             => $result->getData('getForeignKey'),
                'indexes'                 => $result->getIndexes(),
                'group_package'           => $result->getGroupPackage(),
                'group_type'              => $result->getGroupType(),
                'history_type'            => $result->getHistoryType(),
                'register_type'           => $result->getRegisterType(),
                ]
            )->save();

            $order = 1;
            foreach ($result->getColumns() as $column) {
                $this->info("Criando DataRow: ".$column->getColumnName());


                $dataRow = $this->dataRow($modelDataType, $column->getColumnName());
                if (!$dataRow->exists) {
                    if (empty($column->getColumnName())){
                        throw new \Exception('Problema na tabela '.$result->code.' coluna '.print_r($column, true));
                    }
                    $dataRow->fill(
                        [
                        'field'         => $column->getColumnName(),
                        'type'         => $column->getColumnType(),
                        'display_name' => $column->getName(),
                        'required'     => $column->isRequired() ? 1 : 0,
                        'browse'     => $column->isBrowse() ? 1 : 0,
                        'read'     => $column->isRead() ? 1 : 0,
                        'edit'     => $column->isEdit() ? 1 : 0,
                        'add'     => $column->isAdd() ? 1 : 0,
                        'delete'     => $column->isDelete() ? 1 : 0,
                        'details'      => $column->getDetails(),
                        'order' => $order,
                        ]
                    )->save();
                    ++$order;
                }
            }
        }
    }

    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew(
            [
                'data_type_id' => $type->id,
                'field'        => $field,
            ]
        );
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
    protected function dataTypeForCode($code)
    {
        if ($return = DataType::where('name', $code)->first()) {
            return $return;
        }
        if ($return = DataType::where('slug', $code)->first()) {
            return $return;
        }

        return $this->dataType('model_name', $code);
    }
}
