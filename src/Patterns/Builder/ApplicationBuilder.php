<?php

declare(strict_types=1);


namespace Support\Patterns\Builder;

use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Coder\GetSetTrait;
use Support\Components\Database\Schema\Table;
use Log;
use Support\Contracts\Manager\BuilderAbstract;
use Support\Patterns\Entity\ApplicationEntity;
use Illuminate\Database\Eloquent\Collection;
use Support\Elements\Entities\RelationshipEntity;
use Support\Exceptions\Coder\EloquentTableNotExistException;
use Support\Models\Application\DataRow;
use Support\Models\Application\DataType;
use Support\Utils\Extratores\ArrayExtractor;

class ApplicationBuilder extends BuilderAbstract
{
    public static $entityClasser = ApplicationEntity::class;

    public $systemEntity;

    public function requeriments(): void
    {
        $this->systemEntity = \Support\Patterns\Builder\SystemBuilder::makeWithOutput($this->output, '')();
    }

    public function prepare(): void
    {
        $this->entity->system = $this->systemEntity;
        
        $this->entity->mapperParentClasses = $this->systemEntity->mapperParentClasses;
        $this->entity->mapperTableToClasses = $this->systemEntity->mapperTableToClasses;
        $this->entity->mapperClassNameToDataTypeReference = $this->systemEntity->mapperClassNameToDataTypeReference;

    }

    public function builder(): bool
    {

        // dd(
        //     // $this->entity->system->tables,
        //     // $this->entity->relations,
        //     $this->entity->relationsMorphs
        //     // $this->entity->relations['trainner_MorphedByMany_account']['relations']
        // );
        $this->builderEloquentModels();

        /**
         * Constroi Relations
         */
        $this->builderRelations();

        // dd(
        //     $this->entity->models["Informate\Models\Entytys\Fisicos\Weapon"]
        // );
        return true;
        
    }

    public function builderEloquentModels()
    {
        (new Collection($this->entity->system->models))->map(
            function ($result) {

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
        );
    }

    /**
     * @todo fazer entityrelation esta no systembuilder
     */
    private function builderRelations()
    {
        // $this->relationships = (new Collection($this->entity->system->relations))->map(
        //     function ($result) {
        //         $this->builderRelations(
        //             $result
        //         );
        //     }
        // );

        // $eloquentRenders->map(
        //     function ($eloquentData, $className) use ($renderDatabaseArray) {

        //         foreach ($eloquentData['relations'] as $relation) {
        //             if (!isset($relation['origin_table_name']) || empty($relation['origin_table_name'])) {
        //                 $relation['origin_table_name'] = $renderDatabaseArray["Leitoras"]["displayClasses"][$relation['origin_table_class']]["tableName"];
        //             }
        //             if (!isset($relation['related_table_name']) || empty($relation['related_table_name'])) {
        //                 $relation['related_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
        //                     $relation['related_table_class'],
        //                     $renderDatabaseArray,
        //                     '["Leitoras"]["displayClasses"][{{index}}]["tableName"]'
        //                 );
        //             }
        //             $this->relationships[] = new RelationshipEntity($relation);
        //         }
        //     }
        // );
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
