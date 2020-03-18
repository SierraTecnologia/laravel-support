<?php

declare(strict_types=1);


namespace Support\Mount;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Discovers\Database\DatabaseUpdater;
use Support\Discovers\Database\Schema\Column;
use Support\Discovers\Database\Schema\Identifier;
use Support\Discovers\Database\Schema\SchemaManager;
use Support\Discovers\Database\Schema\Table;
use Support\Discovers\Database\Types\Type;
use Support\Parser\ParseModelClass;

use Support\Parser\ComposerParser;

use Support\Entitys\DatabaseEntity;

class ColunMount
{
    protected $eloquentClasses = false;


    protected $renderDatabase = false;


    public function __construct($renderDatabase, $model)
    {
        $this->eloquentClasses = $eloquentClasses;


        $this->render();
    }


    public function render($class)
    {
        
        // $colunasBancoDeDados = 

    }

    /**
     * Cached
     */

}
