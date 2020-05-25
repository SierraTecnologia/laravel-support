<?php

declare(strict_types=1);


namespace Support\Patterns\Parser;

use Support\Components\Database\Types\Type;
use Support\Components\Database\Schema\SchemaManager;

class DatabaseParser
{

    public function __construct()
    {
        $this->parser();
    }

    public function __invoke()
    {
        return $this->tables;
    }
    
}
