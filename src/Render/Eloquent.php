<?php

namespace Support\Render;

use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\TableDiff;
use Support\Discovers\Database\Schema\SchemaManager;
use Support\Discovers\Database\Schema\Table;
use Support\Discovers\Database\Types\Type;
use Support\ClassesHelpers\Development\DevDebug;
use Support\ClassesHelpers\Development\HasErrors;
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
use Support\Discovers\Eloquent\EloquentColumn;
use Support\Parser\ParseModelClass;
use Symfony\Component\Inflector\Inflector;

use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\DBALException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Symfony\Component\Debug\Exception\FatalErrorException;
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
        return $this->modelClass = false;
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
            if (!$parserModelClass->typeIs('model') || !$this->tableData = $parserModelClass->toArray()) {
                // dd($parserModelClass);
                return false;
            }

            $this->tableName = $parserModelClass->getData('table');
            $this->name = $this->getName();
            $this->relations = $this->getRelations();
        } catch(BindingResolutionException $e) {
            // Erro Leve
            $this->setError($e->getMessage());
            
        } catch(SchemaException|DBALException $e) {
            // @todo Tratar, Tabela Nao existe
            $this->setError($e->getMessage());
            
        } catch(\Symfony\Component\Debug\Exception\FatalThrowableError $e) {
            $this->setError($e->getMessage());
            // @todo Armazenar Erro em tabela
            dd($e);
            //@todo fazer aqui
        } catch(\Exception $e) {
            $this->setError($e->getMessage());
            dd($e);
        } catch(\Throwable $e) {
            $this->setError($e->getMessage());
            dd($e);
            // @todo Tratar aqui
        }
        return true;
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
                // $this->setError($this->relations->getError()); @todo PEgar erro do relationsscripts
            }
            // dd($key, (new Relationships($this->modelClass)),(new Relationships($this->modelClass))($key));
            return $this->relations;

        } catch(FatalErrorException $e) {
            $this->setError($e->getMessage());
            // dd($this->model, $method, $e);
            dd($e);
            // @todo Tratar aqui
        }
    }

}
