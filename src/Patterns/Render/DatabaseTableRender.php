<?php

namespace Support\Patterns\Render;


use Support\Contracts\Manager\RenderAbstract;
use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;


class DatabaseTableRender extends RenderAbstract
{
    public static $renderForChildrens = false;

    protected function renderChildrens()
    {
        return [];
    }

    protected function renderData()
    {
        return SchemaManager::listTableDetails($this->parameter);
    }


}
