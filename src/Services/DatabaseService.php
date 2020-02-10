<?php

namespace Support\Services;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Coder\Discovers\Database\DatabaseUpdater;
use Support\Coder\Discovers\Database\Schema\Column;
use Support\Coder\Discovers\Database\Schema\Identifier;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Discovers\Database\Schema\Table;
use Support\Coder\Discovers\Database\Types\Type;

use Support\Coder\Parser\ComposerParser;

class DatabaseService
{

    protected $composerParser = false;
    protected $configModelsAlias = [];

    /**
     * Render
     */
    protected $renderModels = [];
    protected $renderTables = [];
    protected $renderRelations = [];

    public function __construct($configModelsAlias, ComposerParser $composerParser)
    {
        $this->configModelsAlias = $configModelsAlias;
        $this->composerParser = $composerParser;
        
        $this->render();

    }

    public function getAllModels()
    {
        $models = $this->composerParser->returnClassesByAlias($this->configModelsAlias);

        return $models->reject(function($filePath, $class) {
            return !(new \Support\Coder\Discovers\Identificadores\ClasseType($class))->typeIs('model');
        });
    }

    /**
     * Render
     */
    public function render()
    {
        $models = collect($this->getAllModels())->map(function($file, $class) {
            return new EloquentService($class);
        })->values()->all();



        foreach ($models as $model) {
            try {

                $this->renderForModel($model);
                $this->renderForTable($model);

                foreach ($this->renderModels[$model->getModelClass()]['relations'] as $relation) {
                    $this->renderForRelation($model, $relation);
                }

            } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
                // dd($e);
                //@todo fazer aqui
            } catch(\Exception $e) {
                // dd($e);
                // @todo Tratar aqui
            } catch(\Throwable $e) {
                // dd($e);
                // @todo Tratar aqui
            }
        }

        dd($this->renderModels, $this->renderTables, $this->renderRelations);


        return collect($this->renderModels);
    }

    private function renderForModel($model)
    {
        $indice = $model->getModelClass();

        if (!isset($this->renderModels[$indice])) {
            $this->renderModels[$indice] = [
                'name' => $model->getName(),
                'icon' => \Support\Template\Layout\Icons::getForNameAndCache($model->getName()),
                'columns' => $model->getColumns(),
                'relations' => $model->getRelations(),

                'namespace' => $model->getNamespace(),
            ];
        }

        return $this->renderModels;
    }


    private function renderForTable($model)
    {
        /**
         * ^ Support\Services\EloquentService {#979 ▼
         * modelClass: "App\Models\Category"
         * tableName: "categories"
         * colunasDaTabela: null
         * columns: null
         * indexes: null
         * primaryKey: null
         * attributes: null
         * schemaManagerTable: null
         * hardParserModelClass: Support\Coder\Parser\ParseModelClass {#1175 ▶}
         * debug: false
         * modelsForDebug: []
         */
        $indice = $model->getTableName();
        

        if (!isset($this->renderTables[$indice])) {
            $this->renderTables[$indice] = [
                'name' => $model->getName(),
                'icon' => \Support\Template\Layout\Icons::getForNameAndCache($model->getName()),
                'relations' => $model->getRelations(),

                'namespace' => $model->getNamespace(),
                'group' => $model->getNamespace(),
            ];
        }

        return $this->renderTables;
    }

    private function renderForRelation($model, $relation)
    {
        /**
         *  Support\Coder\Discovers\Eloquent\Relationship {#9944 ▼
         *  name: "users"
         *  type: "HasMany"
         *  model: "App\Models\User"
         *  foreignKey: "category_id"
         *  ownerKey: "id"
         */
        $indice = $relation->getType();


        if (!isset($this->renderRelations[$indice])) {
            $this->renderRelations[$indice] = [
                'tables' => [],
                'models' => [],
                'tablesPossuem' => [],
                'modelsPossuem' => [],
            ];
        }

        $this->renderRelations[$indice]['tables'][] = $relation->getName();
        $this->renderRelations[$indice]['models'][] = $relation->getModel();
        $this->renderRelations[$indice]['tablesPossuem'][] = $model->getTableName();
        $this->renderRelations[$indice]['modelsPossuem'][] = $model->getModelClass();
        return $this->renderRelations;
    }

    

    // private function renderCreateIfNotExistRelationLinks($model, $relation)
    // {
        
    //     $indice = $relation->getType();
    //     dd($relation, $model);


    //     if (!isset($this->renderRelations[$indice])) {
    //         $this->renderRelations[$indice] = [
    //             'name' => $model->getName(),
    //             'icon' => \Support\Template\Layout\Icons::getForNameAndCache($model->getName()),
    //             'relations' => $relations,

    //             'namespace' => $model->getNamespace(),
    //             'group' => $model->getNamespace(),
    //         ];
    //     }

    //     return $array;
    // }

}
