<?php

namespace Support\Console\Commands\System;

use Illuminate\Console\Command;
use Support\Components\Coders\Model\Factory;
use Illuminate\Contracts\Config\Repository;
use Support\Services\SystemService;

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
     * @var \Support\Components\Coders\Model\SystemService
     */
    protected $systemService;

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
    public function __construct(SystemService $systemService)
    // public function __construct(Factory $models, Repository $config)
    {
        parent::__construct();

        $this->systemService = $systemService;
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
        $this->info("Check out your models for");






        $render = new \Support\Patterns\Builder\CodeBuilder($this);
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

    // /**
    //  * @return string
    //  */
    // protected function getConnection()
    // {
    //     return $this->option('connection') ?: $this->config->get('database.default');
    // }

    // /**
    //  * @param $connection
    //  *
    //  * @return string
    //  */
    // protected function getSchema($connection)
    // {
    //     return $this->option('schema') ?: $this->config->get("database.connections.$connection.database");
    // }

    // /**
    //  * @return string
    //  */
    // protected function getTable()
    // {
    //     return $this->option('table');
    // }
}
