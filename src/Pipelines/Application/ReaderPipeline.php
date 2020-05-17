<?php

namespace Support\Pipelines\Application;

use League\Pipeline\Pipeline;
use League\Pipeline\StageInterface;

class DatabaseRender implements StageInterface
{
    public function __invoke($eloquentClasses)
    {
        return Cache::remember('sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {
            Log::debug(
                'Mount Database -> Renderizando'
            );
            $renderDatabase = (new \Support\Components\Database\Render\Database($eloquentClasses));
            return $renderDatabase;
        });
    }
}

class DatabaseMount implements StageInterface
{


    public function __invoke($renderDatabaseArray)
    {

        // // Persist Models With Errors @todo retirar ignoretedClasses
        // $this->ignoretedClasses = $this->eloquentClasses->diffKeys($renderDatabaseArray["Leitoras"]["displayClasses"]);
        $eloquentClasses = $this->eloquentClasses = collect($renderDatabaseArray["Leitoras"]["displayClasses"]);
        // dd(
        //     'Olaaaa Database Mount',
        //     $eloquentClasses,
        //     $this->ignoretedClasses 
        // );

        $this->renderDatabase = $renderDatabaseArray;
        
        $this->relationships = $eloquentClasses->map(function($eloquentData, $className) use ($renderDatabaseArray) {

            foreach ($eloquentData['relations'] as $relation) {
                if (!isset($relation['origin_table_name']) || empty($relation['origin_table_name'])) {
                    $relation['origin_table_name'] = $renderDatabaseArray["Leitoras"]["displayClasses"][$relation['origin_table_class']]["tableName"];
                }
                if (!isset($relation['related_table_name']) || empty($relation['related_table_name'])) {
                    $relation['related_table_name'] = ArrayExtractor::returnNameIfNotExistInArray(
                        $relation['related_table_class'],
                        $renderDatabaseArray,
                        '["Leitoras"]["displayClasses"][{{index}}]["tableName"]'
                    );
                }
                return new Relationship($relation);
            }
        });

        $this->entitys = $eloquentClasses->reject(function($eloquentData, $className) {
            return $this->eloquentHasError($className);
        })->map(function($eloquentData, $className) use ($renderDatabaseArray) {
            return (new EloquentMount($className, $renderDatabaseArray))->getEntity();
        });
        //     dd(
        //         $this->entitys,
        //     $this->renderDatabase['AplicationTemp']['tempErrorClasses']
        // );
        
        // $databaseEntity = new DatabaseEntity();
        
        // $databaseEntity = new DatabaseEntity();
        // $databaseEntity

    }
}

class AddOneStage implements StageInterface
{
    public function __invoke($payload)
    {
        return $payload + 1;
    }
}

$pipeline = (new Pipeline)
    ->pipe(new DatabaseRender)
    ->pipe(new DatabaseMount)
    ->pipe(new AddOneStage);

// Returns 21
$pipeline->process(10);



// Re-usable Pipelines
// Because the PipelineInterface is an extension of the StageInterface pipelines can be re-used as stages. This creates a highly composable model to create complex execution patterns while keeping the cognitive load low.

// For example, if we'd want to compose a pipeline to process API calls, we'd create something along these lines:

$processApiRequest = (new Pipeline)
    ->pipe(new ExecuteHttpRequest) // 2
    ->pipe(new ParseJsonResponse); // 3
    
$pipeline = (new Pipeline)
    ->pipe(new ConvertToPsr7Request) // 1
    ->pipe($processApiRequest) // (2,3)
    ->pipe(new ConvertToResponseDto); // 4 
    
$pipeline->process(new DeleteBlogPost($postId));