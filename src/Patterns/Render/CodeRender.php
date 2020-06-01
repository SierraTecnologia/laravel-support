<?php

namespace Support\Patterns\Render;

use Support\Contracts\Manager\RenderAbstract;
use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;
use Illuminate\Support\Collection;

class CodeRender extends RenderAbstract
{

    public static $renderForChildrens = CodeEloquentRender::class;

    protected function renderChildrens(): Collection
    {
        $configModelsAlias = \Illuminate\Support\Facades\Config::get('generators.loader.models_alias', []);
        $composerParser = resolve(\Support\Patterns\Parser\ComposerParser::class);
        
        $eloquentClasses = $composerParser->returnClassesByAlias($configModelsAlias);


        return $eloquentClasses->map(
            function ($filePath, $class) {
                return $class;
            }
        );
    }


    protected function renderData(): array
    {
        $data = [];

        $results = $this->getChildrens();
        foreach($results as $result) {
            $data[$result] = $this->childrenRenders[$result]->getData();
        }
        
        return $data;
    }



}
