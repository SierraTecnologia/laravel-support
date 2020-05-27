<?php

declare(strict_types=1);

namespace Support\Patterns\Builder;


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

use Support\Components\Coders\Parser\ComposerParser;
use Support\Utils\Searchers\ArraySearcher;
use Support\Elements\Entities\EloquentColumn;


use Support\Patterns\Entity\EloquentColumnEntity;
use Support\Contracts\Manager\BuilderAbstract;

class EloquentColumnBuilder extends BuilderAbstract
{
    public static $entityClasser = EloquentColumnEntity::class;

    /**
     * Caso seja ***able_type deve ser ignorado
     */
    public function isToIgnoreColumn()
    {
        return ArraySearcher::arraySearchByAttribute(
            $this->column['name'],
            $this->renderDatabaseData['Dicionario']['dicionarioTablesRelations'],
            'morph_type'
        );
    }

    public function builder()
    {
        if ($this->isToIgnoreColumn()) {
            return false;
        }

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
        $this->entity->setColumnName($this->getColumnName());

        $this->entity->setColumnType($this->column['type']['name']);
        if (!isset($this->column['type']['default']) || !isset($this->column['type']['default']['type'])) {
            // @todo Add Relacionamento Caso Exista
            $this->entity->setColumnType($this->getColumnDisplayType($this->column['type']['name']));
        } else {
            // @todo Add Relacionamento Caso Exista
            $this->entity->setColumnType($this->getColumnDisplayType($this->column['type']['default']['type']));
        }

        $this->entity->setName($this->getName());
        $this->entity->setData($this->column);
        if ($details = $this->mountDetails()) {
            $this->entity->setDetails($details);
        }

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



    // 'details'      => [
    //     'slugify' => [
    //         'origin' => 'title',
    //     ],
    //     'validation' => [
    //         'rule'  => 'unique:pages,slug',
    //     ],
    // ],
    // [
    //     'default' => '',
    //     'null'    => '',
    //     'options' => [
    //         '' => '-- None --',
    //     ],
    //     'relationship' => [
    //         'key'   => 'id',
    //         'label' => 'name',
    //     ],
    // ]

    // Image
    // 
    // 'details'      => [
    //     'resize' => [
    //         'width'  => '1000',
    //         'height' => 'null',
    //     ],
    //     'quality'    => '70%',isBelongTo
    //                 'width'  => '300',
    //                 'height' => '250',
    //             ],
    //         ],
    //     ],
    // ],

    public function mountDetails()
    {
        $haveDetails = false;
        $array = [];
        // $array['options'] = [
        //         '' => '-- None --',
        // ];

        if ($relation = $this->isBelongTo()) {
            if (!isset($this->renderDatabaseData['Mapper']['mapperTableToClasses'][$relation['name']])) {
                return null; //@todo tratar erro de tabela que nao existe
            }
            // name, key, label
            $haveDetails = true;

            if (is_array($relationClassName = $this->renderDatabaseData['Mapper']['mapperTableToClasses'][$relation['name']])) {
                $relationClassName = $relationClassName[0];
            }

            $array['model'] = $relationClassName;
            $array['table'] = $relation['name'];
            $array['method'] = $relation['name'];
            $array['type'] = 'belongsTo';
            $array['column'] = $this->getColumnName();
            $array['key'] = $relation['key'];
            $array['label'] = $relation['displayName'];
            $array['pivot_table'] = $relation['name'];
            $array['pivot'] = 0;
        }

        if ($relation = $this->isMorphTo()) {
            // Filtra o Primeiro
            $relation = $relation[count($relation)-1];

            // @todo Ajeitar aqu dps 
            // if (!isset($relation['table_target']) || !isset($this->renderDatabaseData['Mapper']['mapperTableToClasses'][$relation['table_target']])) {
            //     dd(
            //         'deu ruim aqui mountcolumn',
            //         $relation
            //     );
            //     return null; //@todo tratar erro de tabela que nao existe
            // }
            // name, key, label
            $haveDetails = true;

            // Get Relation Data
            $relationData = $relation['relations'][0];

            // Aqui invez do modelo fica a coluna que armazena o modelo
            $array['model'] = $relationData['morph_type'];
            $array['table'] = $relation['name'];
            $array['method'] = $relation['name'];
            $array['type'] = 'morphTo';
            $array['column'] = $this->getColumnName();
            $array['key'] = $relationData['ownerKey']; // id, code
            $array['label'] = 'name';
            $array['pivot'] = 0;
            if ($relationData['pivot']) {
                $array['pivot'] = 1;
                $array['pivot_table'] = $relationData['pivot'];
                dd(
                    'fazer pivon',
                    $relation,
                    $array
                );
            }
        }

        // Belongs to many
        // if ($relation = $this->isBelongTo()) {
        //     // name, key, label
        //     $haveDetails = true;
        //     $array['model'] = $relationClassName;
        //     $array['table'] = $relation['roles'];
        //     $array['type'] = 'belongsToMany';
        //     $array['column'] = $relation['id'];
        //     $array['key'] = $relation['key'];
        //     $array['label'] = $relation['ladisplayNamebel'];
        //     $array['pivot_table'] = $relation['user_roles'];
        //     $array['pivot'] = 1; // @todo
        //     $array['taggable'] = 0; // @todo
        // }

        if (!$haveDetails) {
            return null;
        }

        return $array;
    }






        /**
         * ^ Illuminate\Support\Collection {#799 ▼
         *   #items: array:6 [▼
         * id" => array:19 [▶]
         * name" => array:21 [▼
           * name" => "name"
           * type" => "varchar"
           * default" => null
           * notnull" => false
           * length" => 255
           * precision" => 10
           * scale" => 0
           * fixed" => false
           * unsigned" => false
           * autoincrement" => false
           * columnDefinition" => null
           * comment" => null
           * charset" => "utf8mb4"
           * collation" => "utf8mb4_unicode_ci"
           * oldName" => "name"
           * null" => "YES"
           * extra" => ""
           * composite" => false
           * field" => "name"
           * indexes" => []
           * key" => null
          *    ]
         * description" => array:21 [▶]
         * created_at" => array:19 [▼
           * name" => "created_at"
           * type" => "timestamp"
           * default" => null
           * notnull" => false
           * length" => 0
           * precision" => 10
           * scale" => 0
           * fixed" => false
           * unsigned" => false
           * autoincrement" => false
           * columnDefinition" => null
           * comment" => null
           * oldName" => "created_at"
           * null" => "YES"
           * extra" => ""
           * composite" => false
           * field" => "created_at"
           * indexes" => []
           * key" => null
          *    ]
         * updated_at" => array:19 [▶]
         * deleted_at" => array:19 [▶]
          *  ]
         * }
         */


    /**
                'details'      => [
                    'model'       => 'Facilitador\\Models\\Role',
                    'table'       => 'roles',
                    'type'        => 'belongsTo',
                    'column'      => 'role_id',
                    'key'         => 'id',
                    'label'       => 'display_name',
                    'pivot_table' => 'roles',
                    'pivot'       => 0,
                ],

     * User hasMany Phones (One to Many)
     * Phone belongsTo User (Many to One) (Inverso do de cima)
     * 
     * belongsToMany (Many to Many) (Inverso é igual)
     * 
     * morphMany
     * morphTo
     * 
     * morphedByMany (O modelo possui a tabela taggables)
     * morphToMany   (nao possui a tabela taggables)
     */

    /**
     * number
     * text
     * text_area
     * rich_text_box
     * 
     * select_dropdown
     * 
     * timestamp
     */
    public function getColumnDisplayType($type)
    {
        if ($this->isRelationship($type)) {
            return 'relationship';
        }

        if ($type == 'varchar') {
            return 'text';
        }
        
        return $type;
    }

    protected function isBelongTo($type = false)
    {
        if (isset($this->renderDatabaseData['Leitoras']['displayTables'][$this->getColumnName()])) {
            return $this->renderDatabaseData['Leitoras']['displayTables'][$this->getColumnName()];
        }

        return false;
    }

    /**
     * name" => "gasto_MorphMany_person"
    "table_origin" => "gastos"
    "table_target" => "persons"
    "pivot" => 0
    "type" => "MorphMany"
    "relations" => array:1 [
      0 => array:12 [
        "origin_table_class" => "Population\Models\Identity\Actors\Person"
        "origin_foreignKey" => "gastoable_id"
        "related_table_class" => "Casa\Models\Economic\Gasto"
        "is_inverse" => false
        "pivot" => false
        "name" => "gastos"
        "type" => "MorphMany"
        "model" => "Casa\Models\Economic\Gasto"
        "ownerKey" => "code"
        "foreignKey" => "gastoable_id"
        "morph_type" => "gastoable_type"
        "related_foreignKey" => "code"
      ]

     */
    protected function isMorphTo($type = false)
    {
        // if ($this->className==\Population\Models\Market\Abouts\Info::class
        // && $this->column['name']!=='id'&& $this->column['name']!=='text'
        // ) {
        if ($searchForeachKey = ArraySearcher::arraySearchByAttribute(
            $this->column['name'],
            // $this->renderDatabaseData['Leitoras']['displayTables'],
                $this->renderDatabaseData['Dicionario']['dicionarioTablesRelations'],
            'foreignKey'
        )
        ) {
            $isMorph = false;
            $found = [];
            foreach ($searchForeachKey as $valorFound) {
                if (in_array($this->renderDatabaseData['Dicionario']['dicionarioTablesRelations'][$valorFound]['type'], ['MorphMany', 'MorphTo'])) {
                    $isMorph = true;
                    $found[] = $this->renderDatabaseData['Dicionario']['dicionarioTablesRelations'][$valorFound];
                }
            }
            // dd($found);
            if ($isMorph) {
                return $found;
            }
        }
        //     dd(
        //         $this->className,
        //         $this->column,
        //         $this->renderDatabaseData
        //     );
        // }
        
        return false;
    }

    /**
     * number
     * text
     * text_area
     * rich_text_box
     * 
     * select_dropdown
     * 
     * timestamp
     */
    protected function isRelationship($type)
    {
        if ($this->isBelongTo($type)) {
            return true;
        }

        return false;
    }
}
