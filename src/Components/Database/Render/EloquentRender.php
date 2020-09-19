<?php

namespace Support\Components\Database\Render;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Muleta\Traits\Debugger\DevDebug;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use App;
use Log;
use Artisan;
use Pedreiro\Elements\DataTypes\Varchar;
use Support\Entities\EloquentColumn;
use Support\Patterns\Parser\ParseModelClass;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
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
use Illuminate\Contracts\Container\BindingResolutionException;

use Support\Contracts\Support\Arrayable;
use Support\Contracts\Support\ArrayableTrait;
use Muleta\Traits\Debugger\HasErrors;
use Muleta\Traits\Coder\GetSetTrait;
use Muleta\Utils\Modificators\StringModificator;

class EloquentRender implements Arrayable
{
    use HasErrors, ArrayableTrait;
    use DevDebug;
    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Identify ClassName
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $modelClass;

    /**
     * Parejt
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    public $parentClass;

    /**
     * Attributes to Array Mapper
     */
    public static $mapper = [
        'tableData',
        'tableName',
        'relations',
        'name',
        'icon',
    ];

    /**
     * Params
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $tableData;

    /**
     * Params
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $tableName;

    /**
     * Params
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $relations = false;

    /**
     * Params
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $name;

    /**
     * Params
     *
     * @var          string
     * @getter       true
     * @setter       false
     * @serializable true
     */
    protected $icon;


    /**
     * Construct
     */
    public function __construct($modelClass = false)
    {
        if (in_array($modelClass, $this->modelsForDebug)) {
            $this->debug = true;
        }
        $this->modelClass = $modelClass;

        if (!empty($this->modelClass) && $this->render()) {
            // if ($modelClass!='Siravel\Models\Access\SocialAuthService')
            // dd($this, $modelClass);
            return true;
        }

        return $this->reportError();
    }

    protected function reportError()
    {
        $this->markWithError();
        return false;
    }

    public static function make($modelClass)
    {
        return new self($modelClass);
    }

    /**
     * Update the table.
     *
     * @return void
     */
    public function render()
    {
        try {
            $parserModelClass = new ParseModelClass($this->modelClass);
            if (!$parserModelClass->typeIs('model')) {
                $this->setDebug('Class not is type model: '.$this->modelClass);
                return false;
            }
            if ($parserModelClass->hasError() || !$this->tableData = $parserModelClass->toArray()) {
                $this->mergeErrors($parserModelClass->getErrors());
                return false;
            }

            $this->tableName = $parserModelClass->getData('table');
            $this->name = $this->generateName();
            $this->icon = $this->generateIcon();
            $this->relations = $this->generateRelations();
            $this->parentClass = $parserModelClass->getData('parentClass');
        } catch(BindingResolutionException $e) {
            // Erro Leve
            $this->setErrors(
                $e,
                [
                    'model' => $this->modelClass
                ]
            );
            
        } catch(SchemaException|DBALException $e) {
            // @todo Tratar, Tabela Nao existe
            $this->setErrors(
                $e,
                [
                    'model' => $this->modelClass
                ]
            );
        } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
            $this->setErrors(
                $e,
                [
                    'model' => $this->modelClass
                ]
            );
        } 
        return true;
    }

    /**
     * Trabalhos Leves
     */
    public function generateName($plural = false)
    {
        $reflection = new ReflectionClass($this->modelClass);
        $name = $reflection->getShortName();

        if ($plural) {
            return StringModificator::pluralize($name);
        }

        return $name;
    }

    public function generateIcon()
    {
        return \Pedreiro\Template\Layout\Icons::getForNameAndCache($this->name, false);
    }


    /**
     * Trabalhos Pesados
     */
    public function generateRelations($key = false)
    {
        try {
            if ($key) {
                return (new RelationshipsRender($this->modelClass))($key);
            }

            if (!$this->relations) {
                $this->relations = (new RelationshipsRender($this->modelClass))($key);
                // $this->setErrors($this->relations->getError()); @todo PEgar erro do relationsscripts
            }
            
            return $this->relations;

        } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
            $this->setErrors($e);
            dd(
                'Erro EloloquentRender generateRelations',
                $e
            );
            // @todo Tratar aqui
        }
    }

}
