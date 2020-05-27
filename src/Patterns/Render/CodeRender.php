<?php

namespace Support\Patterns\Render;

use Support\Contracts\Manager\RenderAbstract;
use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;

class CodeRender extends RenderAbstract
{

    public static $renderForChildrens = CodeEloquentRender::class;

    protected function renderChildrens()
    {
        $configModelsAlias = \Illuminate\Support\Facades\Config::get('sitec.discover.models_alias', []);
        $composerParser = new \Support\Patterns\Parser\ComposerParser();
        
        $eloquentClasses = $composerParser->returnClassesByAlias($configModelsAlias);


        return $eloquentClasses->map(
            function ($filePath, $class) {
                return $class;
            }
        );
    }


    protected function renderData()
    {
        $data = [];

        $results = $this->getChildrens();
        foreach($results as $result) {
            $data[$result] = $this->childrenRenders[$result]->getData();
        }
        
        return $data;
    }



}
