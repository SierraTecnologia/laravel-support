<?php

declare(strict_types=1);


namespace Support\Patterns\Parser;

use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;

class DatabaseTables
{
    /**
     * Nivel 2
     */
    protected function renderTables()
    {
        $this->dicionarioPrimaryKeys = [];
        $tables = [];
        Type::registerCustomPlatformTypes();
        return SchemaManager::listTables();
        // return $this->getSchemaManagerTable()->getIndexes(); //@todo indexe

    }
    
}
