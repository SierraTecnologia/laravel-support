<?php

namespace Support\Console\Commands\System;

use Facilitador\Models\Worker as Command;
use Support\Components\Coders\Model\Factory;
use Illuminate\Contracts\Config\Repository;
use Support\Services\ApplicationService;
use Support\Models\Application\DataRow;
use Support\Models\Application\DataType;

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


        \Support\Models\Code\Classer::truncate();
        DataRow::truncate();
        DataType::query()->delete();

        $entity = \Support\Patterns\Builder\ApplicationBuilder::make('', $this)();

        // $render = new \Support\Patterns\Builder\DatabaseBuilder($this);
        // $render = new \Support\Patterns\Builder\ModelagemBuilder($this);
        // dd(
        //     $render
        // );




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




        // dd(
        //     $this->systemService->render()
        //     // $systemService->render()['persons']->toArray()
        // );

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


    
}
