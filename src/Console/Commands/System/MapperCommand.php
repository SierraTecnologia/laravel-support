<?php

namespace Support\Console\Commands\System;

use Illuminate\Console\Command;
use Support\Components\Coders\Model\Factory;
use Illuminate\Contracts\Config\Repository;
use Support\Services\ApplicationService;
use Support\Models\DataRow;
use Support\Models\DataType;

class MapperCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitecsupport:system:mapper';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mapper models';

    /**
     * @var \Support\Services\ApplicationService
     */
    protected $applicationService;

    /**
     * @var \Support\Components\Coders\Model\Factory
     */
    protected $models;

    /**
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Create a new command instance.
     *
     * @param \Support\Components\Coders\Model\Factory $models
     * @param \Illuminate\Contracts\Config\Repository  $config
     */
    public function __construct(ApplicationService $applicationService)
    // public function __construct(Factory $models, Repository $config)
    {
        parent::__construct();

        $this->applicationService = $applicationService;
        // $this->models = $models;
        // $this->config = $config;



        // dd(
        //     $models,
        //     $config
        // );
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {


        // \Support\Models\Code\Classes::truncate();




        $entity = \Support\Patterns\Builder\ApplicationBuilder::make('', $this)();


        foreach ($entity->models as $eloquentService) {
            
            $modelDataType = $this->dataTypeForCode($eloquentService->getModelClass());
            if (!$modelDataType->exists) {
                $this->info("Criando DataType");
                // Name e Slug sao unicos
                $modelDataType->fill(
                    [
                    'name'                  => $eloquentService->getModelClass(), //strtolower($eloquentService->getName(true)),
                    'slug'                  => $eloquentService->getModelClass(), //strtolower($eloquentService->getName(true)),
                    'display_name_singular' => $eloquentService->getName(false),
                    'display_name_plural'   => $eloquentService->getName(true),
                    'icon'                  => $eloquentService->getIcon(),
                    'model_name'            => $eloquentService->getModelClass(),
                    'controller'            => '',
                    'generate_permissions'  => 1,
                    'description'           => '',
                    'table_name'              => $eloquentService->getTablename(),
                    'key_name'                => $eloquentService->getData('getKeyName'),
                    'key_type'                => $eloquentService->getData('getKeyType'),
                    'foreign_key'             => $eloquentService->getData('getForeignKey'),
                    'group_package'           => $eloquentService->getGroupPackage(),
                    'group_type'              => $eloquentService->getGroupType(),
                    'history_type'            => $eloquentService->getHistoryType(),
                    'register_type'           => $eloquentService->getRegisterType(),
                    ]
                )->save();

                $order = 1;
                foreach ($eloquentService->getColumns() as $column) {
                    // dd(
                    //     $eloquentService->getColumns(),
                    //     $column,
                    //     $column->getData('notnull')
                    // );

                    $dataRow = $this->dataRow($this->modelDataType, $column->getColumnName());
                    if (!$dataRow->exists) {
                        $dataRow->fill(
                            [
                            // 'type'         => 'select_dropdown',
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




        // $render = new \Support\Patterns\Builder\DatabaseBuilder($this);
        // $render = new \Support\Patterns\Builder\ModelagemBuilder($this);
        dd(
            $render
        );




        // dd(
        //     $render
        // );

















        // if (!$this->systemService->getEntity(
        //     \Support\Patterns\Entity\DatabaseEntity::class
        // )) {
        //     $render = \Support\Patterns\Render\DatabaseRender::make('', $this)();
        //     dd(
        //         $render
        //     );
        // }




        dd(
            $this->systemService->render()
            // $systemService->render()['persons']->toArray()
        );

        // $connection = $this->getConnection();
        // $schema = $this->getSchema($connection);
        // $table = $this->getTable();

        // // Check whether we just need to generate one table
        // if ($table) {
        //     $this->models->on($connection)->create($schema, $table);
        //     $this->info("Check out your models for $table");
        // }

        // // Otherwise map the whole database
        // else {
        //     $this->models->on($connection)->map($schema);
        //     $this->info("Check out your models for $schema");
        // }
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
