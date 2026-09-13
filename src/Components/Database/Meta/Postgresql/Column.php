<?php

namespace Support\Components\Database\Meta\Postgresql;

use Illuminate\Support\Fluent;

class Column implements \Support\Components\Database\Meta\Column
{
    protected $metadata;

    protected $metas = [
        'type', 'name', 'autoincrement', 'nullable', 'default', 'comment',
    ];

    public static $mappings = [
        'string' => ['character varying', 'varchar', 'text', 'char', 'character', 'json', 'jsonb', 'uuid'],
        'date' => ['date', 'time', 'time without time zone', 'time with time zone', 'timestamp', 'timestamp without time zone', 'timestamp with time zone'],
        'int' => ['smallint', 'integer', 'bigint', 'int2', 'int4', 'int8'],
        'float' => ['decimal', 'numeric', 'real', 'double precision', 'money'],
        'boolean' => ['boolean', 'bool'],
    ];

    public function __construct($metadata = [])
    {
        $this->metadata = $metadata;
    }

    public function normalize()
    {
        $attributes = new Fluent();

        foreach ($this->metas as $meta) {
            $this->{'parse'.ucfirst($meta)}($attributes);
        }

        return $attributes;
    }

    protected function parseType(Fluent $attributes)
    {
        $dataType = strtolower($this->metadata['type_name'] ?? $this->metadata['type'] ?? 'string');
        $attributes['type'] = $dataType;

        foreach (static::$mappings as $phpType => $databaseTypes) {
            if (in_array($dataType, $databaseTypes, true)) {
                $attributes['type'] = $phpType;
                break;
            }
        }
    }

    protected function parseName(Fluent $attributes)
    {
        $attributes['name'] = $this->metadata['name'];
    }

    protected function parseAutoincrement(Fluent $attributes)
    {
        $attributes['autoincrement'] = (bool) ($this->metadata['auto_increment'] ?? false);
    }

    protected function parseNullable(Fluent $attributes)
    {
        $attributes['nullable'] = (bool) ($this->metadata['nullable'] ?? true);
    }

    protected function parseDefault(Fluent $attributes)
    {
        $attributes['default'] = $this->metadata['default'] ?? null;
    }

    protected function parseComment(Fluent $attributes)
    {
        $attributes['comment'] = $this->metadata['comment'] ?? null;
    }
}
