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
use Support\Patterns\Parser\ParseModelClass;

use Support\Patterns\Parser\ComposerParser;
use Muleta\Utils\Searchers\ArraySearcher;
use Support\Elements\Entities\EloquentColumn;
use Log;

use Support\Patterns\Entity\EloquentColumnEntity;
use Support\Contracts\Manager\BuilderAbstract;

class EloquentColumnBuilder extends BuilderAbstract
{
    public static $entityClasser = EloquentColumnEntity::class;

    /**
     * Caso seja ***able_type deve ser ignorado
     */
    public function isToIgnoreColumn(): bool
    {
        if ($this->entity->code['name'] === 'deleted_at') {
            return true;
        }


        if (ArraySearcher::arraySearchByAttribute(
            $this->entity->code['name'],
            $this->parentEntity->system->relations,
            'morph_type'
        )) {
            return true;
        }
        return false;
    }

    public function builder(): bool
    {

        // dd('Builde',
        //     $this->parentEntity->system,
        //     $this->entity->code
        // );



        if ($this->isToIgnoreColumn()) {
            return false;
        }



        // dd(
        //     $this->entity->code
        //     // $this->parentEntity->system->returnTableForName($this->entity->code['table']),
        // );

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
        $this->entity->setColumnType($this->getColumnType());
        
        $modelClass = $this->parentEntity->system->returnClassForTableName($this->entity->code['table']);
        $this->entity->setIsUpdatedDate(
            $this->getColumnName() ===
            $this->parentEntity->system->models[$modelClass]->getData('getUpdatedAtColumn')
        );
        $this->entity->setIsCreatedDate(
            $this->getColumnName() ===
            $this->parentEntity->system->models[$modelClass]->getData('createdAtColumn')
        );
        

        $this->entity->setName($this->getName());
        $this->entity->setData($this->entity->code);
        if ($details = $this->mountDetails()) {
            $this->entity->setDetails($details);
        }
        return true;

    }

    public function getColumnName(): string
    {
        return $this->entity->code['oldName'];
    }
    /**
     * 
     */
    public function getName(): string
    {
        $explode = explode('_', $this->getColumnName());


        if ($this->isRelationshipType()) {
            array_pop($explode);
        }

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
            $relationData = $relation->toArray();
            if (!isset($this->parentEntity->system->mapperTableToClasses[$relationData['name']])) {
                return null; //@todo tratar erro de tabela que nao existe
            }
            // name, key, label
            $haveDetails = true;

            if (is_array($relationClassName = $this->parentEntity->system->mapperTableToClasses[$relationData['name']])) {
                $relationClassName = $relationClassName[0];
            }

            $array['model'] = $relationClassName;
            $array['table'] = $relationData['name'];
            $array['method'] = $relationData['name'];
            $array['type'] = 'belongsTo';
            $array['column'] = $this->getColumnName();
            $array['key'] = $relation->returnPrimaryKeyFromIndexes();
            $array['label'] = $relation->getDisplayName();
            $array['pivot_table'] = $relationData['name'];
            $array['pivot'] = 0;
        }else if ($relation = $this->isMorphTo()) {
            // Filtra o Primeiro

            // @todo Ajeitar aqu dps 
            // if (!isset($relation['table_target']) || !isset($this->parentEntity->system->mapperTableToClasses[$relation['table_target']])) {
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
                dd('fazer pivon',
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


        
        if (in_array($this->getColumnType(), ['date', 'datetime', 'timestamp'])) {
            $haveDetails = true;
            // $array['format'] = '%A %d %B %Y'; //formatLocalized for Carbon
            // $array['format'] = 'Y-m-d G:i:s';
        }
        
        if (in_array($this->getColumnType(), ['checkbox'])) {
            $haveDetails = true;
            $array['on'] = true;
            $array['off'] = true;
        }
        
        if (in_array($this->getColumnType(), ['select_dropdown', 'select_multiple'])) {
            $haveDetails = true;
            $array['options'] = true;
        }
        
        if (in_array($this->getColumnType(), ['media_picker'])) {
            $haveDetails = true;
            $array['show_as_images'] = true;
        }

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
     * 
     * 'checkbox',
     * 'multiple_checkbox',
     * 'color',
     * 'date',
     * 'file',
     * 'image',
     * 'multiple_images',
     * 'media_picker',
     * 'number',
     * 'password',
     * 'radio_btn',
     * 'rich_text_box',
     * 'code_editor',
     * 'markdown_editor',
     * 'select_dropdown',
     * 'select_multiple',
     * 'text',
     * 'text_area',
     * 'time',
     * 'timestamp',
     * 'hidden',
     * 'coordinates',
     */
    public function getColumnDisplayType(string $type): string
    {
        if ($this->isRelationshipType()) {
            return 'relationship';
        }

        if (in_array($type, ['text', 'json'])) {
            return 'text_area';
        }

        if (in_array($type, ['longtext'])) {
            return 'rich_text_box';
        }

        if (in_array($type, ['point'])) {
            return 'coordinates';
        }
        if (in_array($type, ['enum'])) {
            return 'select_dropdown';
        }

        if (in_array($type, ['integer', 'float', 'bigint', 'number'])) {
            return 'number';
        }

        if (in_array($type, ['timestamp'])) {
            return 'timestamp';
        }

        if (in_array($type, ['date', 'datetime'])) {
            return 'date';
        }

        if (in_array($type, [
            'varchar',
            'character varying',
            'varying', 'character', 'char'
        ])) {
            return 'text';
        }

        Log::channel('sitec-support')->error(
            'EloquentBuilder: Não tratando tipo '.$type
        );
        return 'text';
    }
    public function getColumnType(): string
    {
        if (isset($this->entity->code['type']['default']) && isset($this->entity->code['type']['default']['type'])) {
            return $this->getColumnDisplayType($this->entity->code['type']['default']['type']);
        }
        return $this->getColumnDisplayType($this->entity->code['type']['name']);
    }
    public function isRelationshipType(): bool
    {
        return $this->isBelongTo() || $this->isMorphTo();
    }

    protected function isBelongTo()
    {
        if (isset($this->parentEntity->system->tables[$this->getColumnName()])) {
            return $this->parentEntity->system->tables[$this->getColumnName()];
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
        "origin_table_class" => "Telefonica\Models\Actors\Person"
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
    protected function isMorphTo()
    {


        // if (isset($this->parentEntity->system->relationsMorphs[$this->getColumnName()])) {
        //     return $this->parentEntity->system->relationsMorphs[$this->getColumnName()];
        // }

        /**
         * Old Verifica pelo Atributo
         */
        // if ($this->className==\Population\Models\Market\Abouts\Info::class
        // && $this->entity->code['name']!=='id'&& $this->entity->code['name']!=='text'
        // ) {
        if ($searchForeachKey = ArraySearcher::arraySearchByAttribute(
            $this->entity->code['name'],
            // $this->parentEntity->system->tables,
                $this->parentEntity->system->relations,
            'foreignKey'
        )
        ) {
            $isMorph = false;
            $found = [];
            foreach ($searchForeachKey as $valorFound) {
                if (in_array($this->parentEntity->system->relations[$valorFound]['type'], ['MorphMany', 'MorphTo'])) {
                    $isMorph = true;
                    $found[] = $this->parentEntity->system->relations[$valorFound];
                }
            }
            // dd($found);
            if ($isMorph) {
                return $found[count($found)-1];
            }
        }
        //     dd(
        //         $this->className,
        //         $this->entity->code,
        //         $this->renderDatabaseData
        //     );
        // }

        if (strpos($this->getColumnName(), 'able') !== false) {
            Log::channel('sitec-support')->warning(
                'Problema no morph para coluna '.$this->getColumnName()
            );
            // dd(
            //     $this->getColumnName(),
            //     'debug1'
            // );
        }
        
        return false;
    }

}
