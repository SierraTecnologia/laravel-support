<?php

namespace Support\Pipelines\Application;

use League\Pipeline\Pipeline;
use League\Pipeline\StageInterface;

// class DatabaseRender implements StageInterface
// {
//     public function __invoke($eloquentClasses)
//     {
//         return Cache::remember('sitec_support_render_database_'.md5(implode('|', $eloquentClasses->values()->all())), 30, function () use ($eloquentClasses) {
//             Log::debug(
//                 'Mount Database -> Renderizando'
//             );
//             $renderDatabase = (new \Support\Components\Database\Render\Database($eloquentClasses));
//             return $renderDatabase;
//         });
//     }
// }

// class DatabaseMount implements StageInterface
// {


//     public function __invoke($renderDatabaseArray)
//     {
//         $eloquentClasses =  collect($renderDatabaseArray["Leitoras"]["displayClasses"]);


//         $this->entitys = $eloquentClasses->reject(function($eloquentData, $className) {
//             return $this->eloquentHasError($className);
//         })->map(function($eloquentData, $className) use ($renderDatabaseArray) {
//             return (new EloquentMount($className, $renderDatabaseArray))->getEntity();
//         });
//     }
// }
class ReaderPipeline
{
    public function __invoke($eloquentClasses)
    {

        $pipeline = (new Pipeline)
            ->pipe(new DatabaseRender)
            ->pipe(new DatabaseMount);
        
        // Returns 21
        $entitys = $pipeline->process(10);
        
        
        
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
    }
}
