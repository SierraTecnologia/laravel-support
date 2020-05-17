<?php

namespace Support\Components\Database\Render;

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

    protected $tables;

    public function run()
    {

    }



    protected function renderTables()
    {
        $this->dicionarioPrimaryKeys = [];

        $tables = [];
        Type::registerCustomPlatformTypes();
        $listTables = SchemaManager::listTables();
        // return $this->getSchemaManagerTable()->getIndexes(); //@todo indexe


        foreach ($listTables as $listTable){
            $columns = ArrayModificator::includeKeyFromAtribute($listTable->exportColumnsToArray(), 'name');
            $indexes = $listTable->exportIndexesToArray();

            // Salva Primaria
           
            if (!$primary = $this->loadMapperPrimaryKeysAndReturnPrimary($listTable->getName(), $indexes)) {
                // @todo VEridica aqui
                // $this->setWarnings(
                //     'Tabela sem primary key: '.$listTable->getName(),
                //     [
                //         'table' => $listTable->getName(),
                //     ],
                //     [
                //         'indexes' => $indexes
                //     ]
                // );

                $this->tempAppTablesWithNotPrimaryKey[$listTable->getName()] = [
                    'name' => $listTable->getName(),
                    'columns' => $columns,
                    'indexes' => $indexes
                ];

            } else {
                $tables[$listTable->getName()] = [
                    'name' => $listTable->getName(),
                    'columns' => $columns,
                    'indexes' => $indexes
                ];

                // Qual coluna ira mostrar em uma Relacao ?
                if ($listTable->hasColumn('name')) {
                    $tables[$listTable->getName()]['displayName'] = 'name';
                } else if ($listTable->hasColumn('displayName')) {
                    $tables[$listTable->getName()]['displayName'] = 'displayName';
                } else {
                    $achou = false;
                    foreach ($tables[$listTable->getName()]['columns'] as $column) {
                        if ($column['type']['name'] == 'varchar') {
                            $tables[$listTable->getName()]['displayName'] = $column['name'];
                            $achou = true;
                            break;
                        }
                    }
                    if (!$achou) {
                        $tables[$listTable->getName()]['displayName'] = $primary;
                    }
                }
            }
        }

        $this->displayTables = $tables;
    }


}
