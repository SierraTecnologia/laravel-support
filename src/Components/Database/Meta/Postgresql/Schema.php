<?php

namespace Support\Components\Database\Meta\Postgresql;

use Illuminate\Database\Connection;
use Illuminate\Support\Fluent;
use Support\Components\Database\Meta\Blueprint;

class Schema implements \Support\Components\Database\Meta\Schema
{
    protected $schema;

    protected $connection;

    protected $tables = [];

    public function __construct($schema, $connection)
    {
        $this->schema = $schema;
        $this->connection = $connection;
        $this->load();
    }

    public function manager()
    {
        return $this->connection->getSchemaBuilder();
    }

    protected function load()
    {
        foreach ($this->fetchTables() as $table) {
            $blueprint = new Blueprint($this->connection->getName(), $this->schema, $table);
            $this->fillColumns($blueprint);
            $this->fillConstraints($blueprint);
            $this->tables[$table] = $blueprint;
        }
    }

    protected function fetchTables()
    {
        return array_column($this->manager()->getTables($this->schema), 'name');
    }

    protected function fillColumns(Blueprint $blueprint)
    {
        foreach ($this->manager()->getColumns($blueprint->qualifiedTable()) as $column) {
            $blueprint->withColumn((new Column($column))->normalize());
        }
    }

    protected function fillConstraints(Blueprint $blueprint)
    {
        $this->fillPrimaryKey($blueprint);
        $this->fillIndexes($blueprint);
        $this->fillRelations($blueprint);
    }

    protected function fillPrimaryKey(Blueprint $blueprint)
    {
        foreach ($this->manager()->getIndexes($blueprint->qualifiedTable()) as $index) {
            if ($index['primary']) {
                $blueprint->withPrimaryKey(new Fluent([
                    'name' => 'primary',
                    'index' => $index['name'],
                    'columns' => $index['columns'],
                ]));

                return;
            }
        }
    }

    protected function fillIndexes(Blueprint $blueprint)
    {
        foreach ($this->manager()->getIndexes($blueprint->qualifiedTable()) as $index) {
            if ($index['primary']) {
                continue;
            }

            $blueprint->withIndex(new Fluent([
                'name' => $index['unique'] ? 'unique' : 'index',
                'columns' => $index['columns'],
                'index' => $index['name'],
            ]));
        }
    }

    protected function fillRelations(Blueprint $blueprint)
    {
        foreach ($this->manager()->getForeignKeys($blueprint->qualifiedTable()) as $foreignKey) {
            $blueprint->withRelation(new Fluent([
                'name' => 'foreign',
                'index' => $foreignKey['name'],
                'columns' => $foreignKey['columns'],
                'references' => $foreignKey['foreign_columns'],
                'on' => [
                    'database' => $foreignKey['foreign_schema'],
                    'table' => $foreignKey['foreign_table'],
                ],
            ]));
        }
    }

    public static function schemas(Connection $connection)
    {
        $schema = $connection->getConfig('schema') ?: 'public';

        return is_array($schema) ? $schema : [$schema];
    }

    public function schema()
    {
        return $this->schema;
    }

    public function has($table)
    {
        return array_key_exists($table, $this->tables);
    }

    public function tables()
    {
        return $this->tables;
    }

    public function table($table)
    {
        if (! $this->has($table)) {
            throw new \InvalidArgumentException("Table [$table] does not belong to schema [{$this->schema}]");
        }

        return $this->tables[$table];
    }

    public function connection()
    {
        return $this->connection;
    }

    public function referencing(Blueprint $table)
    {
        $references = [];

        foreach ($this->tables as $blueprint) {
            foreach ($blueprint->references($table) as $reference) {
                $references[] = [
                    'blueprint' => $blueprint,
                    'reference' => $reference,
                ];
            }
        }

        return $references;
    }
}
