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

use Support\Entitys\EloquentColumn;

class ColunMount
{
    /**
     * Identify
     */
    protected $className;
    protected $column;
    protected $renderDatabaseData;

    /**
     * Construct
     */
    public function __construct($className, $column, $renderDatabase)
    {
        $this->className = $className;
        $this->column = $column;
        $this->renderDatabaseData = $renderDatabase;

        dd(
            $className, $column, $renderDatabase
        );
    }

    public function getEntity()
    {
        $columnEntity = new EloquentColumn();

        /**
         *   "type" => array:3 [▶]
         *   "default" => null
         *   "notnull" => true
         *   "length" => null
         *   "precision" => 10
         *   "scale" => 0
         *   "fixed" => false
         *   "unsigned" => true
         *   "autoincrement" => true
         *   "columnDefinition" => null
         *   "comment" => null
         *   "oldName" => "id"
         *   "null" => "NO"
         *   "extra" => "auto_increment"
         *   "composite" => false
         */
        $columnEntity->setColumnName($this->getColumnName());
        $columnEntity->setColumnType($this->column['type']);
        $columnEntity->setName($this->getName());

        return $columnEntity;
    }

    public function getColumnName()
    {
        return $this->column['oldName'];
    }
    /**
     * 
     */
    public function getName()
    {
        $explode = explode('_', $this->getColumnName());
        $name = '';
        foreach ($explode as $value) {
            if (!empty($name)) {
                $name .= ' ';
            }
            $name .= ucfirst($value);
        }
        return $name;
    }


}
