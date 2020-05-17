<?php
/**
 * Recebe parametros e responde com o Entity Correspondente ou Gera um
 */

declare(strict_types=1);



namespace Support\Patterns\Manager;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Support\Result\RelationshipResult;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Support\Components\Database\DatabaseUpdater;
use Support\Components\Database\Schema\Column;
use Support\Components\Database\Schema\Identifier;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Support\Components\Coders\Parser\ParseModelClass;
use Support\Utils\Modificators\StringModificator;
use Support\Utils\Extratores\ArrayExtractor;
use Support\Components\Coders\Parser\ComposerParser;

use Support\Elements\Entities\DatabaseEntity;
use Support\Elements\Entities\EloquentEntity;
use Support\Elements\Entities\Relationship;
use Illuminate\Support\Facades\Cache;

use Log;

class DatabaseManager
{
    protected $params;


    protected function render()
    {
        Log::debug(
            'Mount Database -> Renderizando'
        );
        $renderDatabase = (new \Support\Components\Database\Render\Database($eloquentClasses));
        return $renderDatabase->toArray();
    }
}
