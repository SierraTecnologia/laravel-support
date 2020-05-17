<?php
namespace Support\Patterns\Writer;

use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Exception;
use ErrorException;
use LogicException;
use OutOfBoundsException;
use RuntimeException;
use TypeError;
use Throwable;
use Watson\Validating\ValidationException;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Inflector\Inflector;
use Illuminate\Support\Collection;
use Support\Services\EloquentService;
use Support\Components\Coders\Parser\ComposerParser;
use Illuminate\Support\Facades\Cache;
use Support\Elements\Entities\Relationship;
use Support\Components\Database\Types\Type;
use Log;
use Support\Components\Database\Schema\SchemaManager;
use Support\Utils\Modificators\ArrayModificator;
use Support\Utils\Inclusores\ArrayInclusor;
use Support\Utils\Modificators\StringModificator;
use Support\Traits\Debugger\HasErrors;

use Support\Components\Coders\Parser\ParseClass;

class Database
{
    use HasErrors;

    /****************************************
     * Eloquent CLasse (Work and Register in Databse)
     **************************************/
    public $eloquentClasses;


}
