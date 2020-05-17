<?php

namespace Support\Pipelines\Application;

use League\Pipeline\Pipeline;
use League\Pipeline\StageInterface;

class DatabasePipeline
{
    public function run($eloquentClasses)
    {
        

        $tables = (new Pipeline)
            // Retorna Todas as Tabelas
            ->pipe(new TablesParser)
            // Mapea as Tabelas
            ->pipe(new TablesBuilder)
            // Builda e Retorna o Entity
            ->pipe(new TablesMount);

        // Returns 21
        $entitys = $pipeline->process(10);



    }
    public function runTwo($eloquentClasses)
    {
        

        $tables = (new Pipeline)
            ->pipe(new DatabaseRender)
            ->pipe(new DatabaseMount);

        // Returns 21
        $entitys = $pipeline->process(10);



        // Re-usable Pipelines
        // Because the PipelineInterface is an extension of the StageInterface pipelines can be re-used as stages. This creates a highly composable model to create complex execution patterns while keeping the cognitive load low.

        // For example, if we'd want to compose a pipeline to process API calls, we'd create something along these lines:

        $databaseEntity = (new Pipeline)
            ->pipe(new ExecuteHttpRequest) // 2
            ->pipe(new ParseJsonResponse); // 3
            
        $pipeline = (new Pipeline)
            ->pipe(new ConvertToPsr7Request) // 1
            ->pipe($processApiRequest) // (2,3)
            ->pipe(new ConvertToResponseDto); // 4 
            
        $pipeline->process(new DeleteBlogPost($postId));
    }
}