<?php

namespace Support\Patterns\Render;

use Support\Contracts\Manager\RenderAbstract;
use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;



class DatabaseRender extends RenderAbstract
{

    public static $renderForChildrens = DatabaseTableRender::class;

    protected function renderChildrens()
    {
        Type::registerCustomPlatformTypes();
        return SchemaManager::manager()->listTableNames();
    }


    protected function renderData()
    {
        $data = [];

        $results = $this->getChildrens();
        foreach($results as $result) {
            $data[$result] = $this->childrenRenders[$result]->getData(); //->toArray();
        }
        
        return $data;
    }


}
