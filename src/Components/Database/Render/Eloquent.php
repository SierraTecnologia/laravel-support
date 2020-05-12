<?php

namespace Support\Components\Database\Render;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Components\Database\Schema\SchemaManager;
use Support\Components\Database\Schema\Table;
use Support\Components\Database\Types\Type;
use Support\Helpers\Development\DevDebug;
use Support\Helpers\Development\HasErrors;
use Illuminate\Database\Eloquent\Relations\Relation;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use App;
use Log;
use Artisan;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Elements\Entities\EloquentColumn;
use Support\Components\Coders\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

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

class Eloquent
{
    use DevDebug;
    use HasErrors;
    /**
     * Identify
     */
    protected $modelClass;

    /**
     * Cached
     */
    protected $name;
    protected $icon;
    protected $tableData;
    protected $tableName;
    protected $colunasDaTabela;
    protected $columns;
    protected $indexes;
    protected $primaryKey;
    protected $attributes;

    protected $relations = false;

    /**
     * NOt Cached
     */
    protected $schemaManagerTable;
    protected $hardParserModelClass;


    /**
     * Other Datas
     */
    public $parentClass;



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

    /**
     * Static functions
     */ 
    public static function makeFromArray($array)
    {
        // @todo
        // return new self($modelClass);
    }
    public static function make($modelClass)
    {
        return new self($modelClass);
    }

    public function getModelClass()
    {
        return $this->modelClass;
    }

    public function getTableName()
    {
        return $this->tableName;
    }
    /**
     * Trabalhos Leves
     */
    public function getName($plural = false)
    {
        $reflection = new ReflectionClass($this->modelClass);
        $name = $reflection->getShortName();

        // @todo Fazer plural
        if ($plural) {
            $name = Inflector::pluralize($name);
            if (is_array($name)) {
                $name = $name[count($name) - 1];
            }
        }

        return $name;
    }


    /**
     * Update the table.
     *
     * @return void
     */
    public function fromArray($data)
    {
        // $manager['modelManager'] = $this->hardParserModelClass->toArray();
        // $manager['tableManager'] = $this->schemaManagerTable->toArray();
    }


    /**
     * Update the table.
     *
     * @return void
     */
    public function toArray()
    {
        $array = [];
        $array['tableName'] = $this->tableName;
        $array['tableData'] = $this->tableData;
        $array['name'] = $this->name;
        $array['icon'] = $this->icon;
        $array['relations'] = $this->relations;
        return $array;
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
                return false;
            }
            if ($parserModelClass->hasError() || !$this->tableData = $parserModelClass->toArray()) {
                Log::channel('sitec-support')->info(
                    'Eloquent Render (HavaError ou eh do tipo model ou del merda no parser): '.
                    $this->modelClass
                );
                $this->mergeErrors($parserModelClass->getErrors());
                return false;
            }

            $this->tableName = $parserModelClass->getData('table');
            $this->name = $this->getName();
            $this->icon = $this->getIcon();
            $this->relations = $this->getRelations();
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
    public function getIcon()
    {
        return \Support\Template\Layout\Icons::getForNameAndCache($this->name, false);
    }

    /**
     * Trabalhos Pesados
     */
    public function getRelations($key = false)
    {
        try {
            if ($key) {
                return (new Relationships($this->modelClass))($key);
            }

            if (!$this->relations) {
                $this->relations = (new Relationships($this->modelClass))($key);
                // $this->setErrors($this->relations->getError()); @todo PEgar erro do relationsscripts
            }
            
            // dd($key, (new Relationships($this->modelClass)),(new Relationships($this->modelClass))($key));
            return $this->relations;

        } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
            $this->setErrors($e);
            // dd($this->model, $method, $e);
            dd($e);
            // @todo Tratar aqui
        }
    }

}
