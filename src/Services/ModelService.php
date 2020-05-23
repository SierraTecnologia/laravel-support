<?php
/**
 * Serviço referente a linha no banco de dados
 */

namespace Facilitador\Services;

use SierraTecnologia\Crypto\Services\Crypto;
use Illuminate\Http\Request;
use Support\Elements\Entities\Relationships;
use App;
use Log;
use Exception;
use Artisan;
use Illuminate\Support\Collection;
use Support\Elements\Entities\DataTypes\Varchar;
use Support\Elements\Entities\EloquentColumn;
use ReflectionClass;
use Support\Components\Database\Schema\SchemaManager;
use Support\Services\EloquentService;
use Facilitador\Routing\UrlGenerator;
use Support\Models\DataRow;
use Support\Models\DataType;
use Support\Services\DatabaseService;
use Support\Elements\Entities\EloquentEntity;
use Support\Models\Code\Classes;

/**
 * ModelService helper to make table and object form mapping easy.
 */
class ModelService
{
    protected $repository = false;
    protected $modelDataType = false;
    protected $modelClass;

    protected $eloquentEntity = false;
    protected $isInitFromClassString = true;

    public function __construct($modelClass = false)
    {
        if ($this->modelClass = $modelClass) {
            if (!is_string($modelClass) && !is_a($modelClass, EloquentEntity::class)) {
                throw new Exception(
                    "Essa classe deveria ser uma string ou uma instancia eloquentEntity: ".print_r($modelClass, true),
                    400
                );
            }
            if (empty($modelClass)) {
                throw new Exception(
                    "ModelService, nao deveria vir vazia a classeModel: ".print_r($modelClass, true),
                    400
                );
            }

            if (is_a($modelClass, EloquentEntity::class)) {
                $this->setEloquentEntity($modelClass);
            }
            $this->getDiscoverService();
        }
    }

    /**
     * @todo isso repete deve ter um contrato compartilhado com repository
     */
    public function getModelService()
    {
        return $this;
    }
    public function getDiscoverService()
    {
        if (!$this->modelDataType) {
            if (!$eloquentService = $this->getEloquentEntity()) {
                // @todo tratar erro
                // dd(
                //     'IhhNaoTem',
                //     $this->modelClass,
                //     debug_backtrace()
                // );
                return false;
            }
            $name = 

            $this->modelDataType = $this->dataTypeForCode($eloquentService->getModelClass());
            if (!$this->modelDataType->exists) {
                // Name e Slug sao unicos
                $this->modelDataType->fill(
                    [
                    'name'                  => $eloquentService->getModelClass(), //strtolower($eloquentService->getName(true)),
                    'slug'                  => $eloquentService->getModelClass(), //strtolower($eloquentService->getName(true)),
                    'display_name_singular' => $eloquentService->getName(false),
                    'display_name_plural'   => $eloquentService->getName(true),
                    'icon'                  => $eloquentService->getIcon(),
                    'model_name'            => $eloquentService->getModelClass(),
                    'controller'            => '',
                    'generate_permissions'  => 1,
                    'description'           => '',
                    'table_name'              => $eloquentService->getTablename(),
                    'key_name'                => $eloquentService->getData('getKeyName'),
                    'key_type'                => $eloquentService->getData('getKeyType'),
                    'foreign_key'             => $eloquentService->getData('getForeignKey'),
                    'group_package'           => $eloquentService->getGroupPackage(),
                    'group_type'              => $eloquentService->getGroupType(),
                    'history_type'            => $eloquentService->getHistoryType(),
                    'register_type'           => $eloquentService->getRegisterType(),
                    ]
                )->save();

                $order = 1;
                foreach ($eloquentService->getColumns() as $column) {
                    // dd(
                    //     $eloquentService->getColumns(),
                    //     $column,
                    //     $column->getData('notnull')
                    // );

                    $dataRow = $this->dataRow($this->modelDataType, $column->getColumnName());
                    if (!$dataRow->exists) {
                        $dataRow->fill(
                            [
                            // 'type'         => 'select_dropdown',
                            'type'         => $column->getColumnType(),
                            'display_name' => $column->getName(),
                            'required'     => $column->isRequired() ? 1 : 0,
                            'browse'     => $column->isBrowse() ? 1 : 0,
                            'read'     => $column->isRead() ? 1 : 0,
                            'edit'     => $column->isEdit() ? 1 : 0,
                            'add'     => $column->isAdd() ? 1 : 0,
                            'delete'     => $column->isDelete() ? 1 : 0,
                            'details'      => $column->getDetails(),
                            'order' => $order,
                            ]
                        )->save();
                        ++$order;
                    }
                }
            }
        }
        return $this->modelDataType;
    }

    public function getPrimaryKey()
    {
        return $this->getDiscoverService()->getPrimaryKey();   
    }

    /**
     * Verificadores
     *
     * @param  [type] $modelClass
     * @return boolean
     */
    public function isModelClass($modelClass)
    {
        return $this->modelClass == $modelClass;
    }

    /**
     * Atributos da Classe
     *
     * @return void
     */
    public function getUrl($page = '')
    {
        return UrlGenerator::managerRoute($this->modelClass, $page);
    }


    public function getCryptName()
    {
        return Crypto::shareableEncrypt($this->modelClass);
    }

    public function getModelClass()
    {
        if (empty($this->modelClass)) {
            return false;
        }

        // dd(
        //     $this->modelClass, Crypto::isCrypto($this->modelClass),
        //     Crypto::shareableDecrypt($this->modelClass),
        //     \Auth::user()
        // );
        if (Crypto::isCrypto($this->modelClass)) {
            $this->modelClass = Crypto::shareableDecrypt($this->modelClass);
        }
        if (empty($this->modelClass)) {
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            // return redirect()->route('facilitador.dash');

            throw new Exception('Criptografia inválida ' . $this->modelClass);
        }

        // return Classes::getFinalClass($this->modelClass);
        // return Classes::getClassWithProcuracao($this->modelClass);
        return $this->modelClass;
    }

    /**
     * Contagens E querys
     */
    public function getModelQuery()
    {
        return $this->modelClass::query();
    }

    /**
     * Campos
     *
     * @return void
     */
    public function getFieldForForm()
    {
        $atributes = $this->getColumnsForForm();
        $formGroup = 'identity';
        $fieldsArray = [];

        foreach ($atributes as $atribute) {
            if (!isset($fieldsArray[$formGroup])) {
                $fieldsArray[$formGroup] = [];
            }
            //@todo COnsertando erro pois da conflito
            if ($atribute->getName()=='name') {
                continue;
            }
            $nameType = $atribute->getType()->getName();
            $fieldsArray[$formGroup][$atribute->getName()] = [];
            $fieldsArray[$formGroup][$atribute->getName()]['type'] = $nameType;
                // 'class' => 'redactor',
                // 'alt_name' => 'Content',

            // // Caso seja Data // @todo Removido
            // if ($nameType = 'datetime') {
            //     $fieldsArray[$formGroup][$atribute->getName()]['type'] = 'string';
            //     $fieldsArray[$formGroup][$atribute->getName()]['class'] = 'datetimepicker';
            // }
        }

        // dd( $fieldsArray);
        // return $fieldsArray;  // @todo Sections nao funcionando
        return $fieldsArray[$formGroup];

        // return [
        //     'identity' => [
        //         'title' => [
        //             'type' => 'string',
        //         ],
        //         'url' => [
        //             'type' => 'string',
        //         ],
        //         'tags' => [
        //             'type' => 'string',
        //             'class' => 'tags',
        //         ],
        //     ],
        //     'content' => [
        //         'entry' => [
        //             'type' => 'text',
        //             'class' => 'redactor',
        //             'alt_name' => 'Content',
        //         ],
        //         'hero_image' => [
        //             'type' => 'file',
        //             'alt_name' => 'Hero Image',
        //         ],
        //     ],
        //     'seo' => [
        //         'seo_description' => [
        //             'type' => 'text',
        //             'alt_name' => 'SEO Description',
        //         ],
        //         'seo_keywords' => [
        //             'type' => 'string',
        //             'class' => 'tags',
        //             'alt_name' => 'SEO Keywords',
        //         ],
        //     ],
        //     'publish' => [
        //         'is_published' => [
        //             'type' => 'checkbox',
        //             'alt_name' => 'Published',
        //         ],
        //         'published_at' => [
        //             'type' => 'string',
        //             'class' => 'datetimepicker',
        //             'alt_name' => 'Publish Date',
        //             'custom' => 'autocomplete="off"',
        //             'after' => '<span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>',
        //         ],
        //     ],
        // ];
    }



    /**
     * Caracteristicas das Tabelas
     */

    public function getColumnsForForm()
    {
        // dd($this->getDiscoverService()->getColumns(), $this->getDiscoverService()->schemaManagerTable->toArray(), $this->getDiscoverService()->getColumns());
        return $this->getDiscoverService()->getColumns();
    }

    public function getRelationsByGroup()
    {

        $classes = $this->getDiscoverService()->getRelations();
        
        $group = [];
        foreach ($classes as $class) {
            if (!isset($group[$class->type])) {
                $group[$class->type] = [];
            }
            $group[$class->type][] = $class;
        }
        return $group;
    }



    /**
     * [dataRow description].
     *
     * @param [type] $type  [description]
     * @param [type] $field [description]
     *
     * @return [type] [description]
     */
    protected function dataRow($type, $field)
    {
        return DataRow::firstOrNew(
            [
                'data_type_id' => $type->id,
                'field'        => $field,
            ]
        );
    }

    /**
     * [dataType description].
     *
     * @param [type] $field [description]
     * @param [type] $for   [description]
     *
     * @return [type] [description]
     */
    protected function dataType($field, $for)
    {
        return DataType::firstOrNew([$field => $for]);
    }
    protected function dataTypeForCode($code)
    {
        if ($return = DataType::where('name', $code)->first()) {
            return $return;
        }
        if ($return = DataType::where('slug', $code)->first()) {
            return $return;
        }

        return $this->dataType('model_name', $code);
    }
    

    /**
     * Helpers
     */

    public function setEloquentEntity($class)
    {
        $this->eloquentEntity = $class;
        $this->modelClass = $class->getModelClass();
        $this->isInitFromClassString = false;
    }

    public function getEloquentEntity()
    {
        if ($this->eloquentEntity) {
            return $this->eloquentEntity;
        }
        return $this->eloquentEntity = $this->getDatabaseService()->forceGetEloquentEntityFromClassName($this->getModelClass());
    }

    public function getDatabaseService()
    {
        return resolve(\Support\Services\DatabaseService::class);
    }

    public function getRepository()
    {
        if (!$this->repository) {
            $this->repository = new RepositoryService($this);
        }
        return $this->repository;
    }

    /**
     * Getter and Setters
     */
    public function getName($plural = false)
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getName($plural);
    }
    public function getIcon()
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getIcon();
    }
    public function getGroupPackage()
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getGroupPackage();
    }
    public function getGroupType()
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getGroupType();
    }
    public function getHistoryType()
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getHistoryType();
    }
    public function getRegisterType()
    {
        if (!$this->getDiscoverService()) {
            return '';
        }
        return $this->getDiscoverService()->getRegisterType();
    }
}
