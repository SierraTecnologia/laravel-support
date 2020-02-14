<?php

namespace Support\Services;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Coder\Discovers\Database\DatabaseUpdater;
use Support\Coder\Discovers\Database\Schema\Column;
use Support\Coder\Discovers\Database\Schema\Identifier;
use Support\Coder\Discovers\Database\Schema\SchemaManager;
use Support\Coder\Discovers\Database\Schema\Table;
use Support\Coder\Discovers\Database\Types\Type;
use Support\Coder\Parser\ParseModelClass;

use Support\Coder\Parser\ComposerParser;

class DatabaseService
{

    protected $databaseMount = false;

    public function __construct($configModelsAlias, ComposerParser $composerParser)
    {
        $this->databaseMount = new \Support\Mounters\SchemaMount($configModelsAlias, $composerParser);
    }

    public function getAllModels()
    {
        return $this->databaseMount->getAllModels();
    }


}
