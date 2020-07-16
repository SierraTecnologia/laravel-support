<?php

namespace Support\Services;

use Support\Components\Database\Mount\DatabaseMount;
use Support\Elements\Entities\EloquentEntity;
use Support\Patterns\Parser\ComposerParser;
use Illuminate\Support\Collection;
use Support\Exceptions\Coder\EloquentNotExistException;
use Support\Exceptions\Coder\EloquentHasErrorException;
use Illuminate\Http\Request;
use Support\Services\ModelService;
use Support\Models\Application\DataType;
use Support\Traits\Coder\GetSetTrait;
use App;
use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Components\Database\Schema\SchemaManager;
use Support\Events\BreadDataAdded;
use Support\Events\BreadDataDeleted;
use Support\Events\BreadDataRestored;
use Support\Events\BreadDataUpdated;
use Support\Events\BreadImagesDeleted;
use Facilitador\Facades\Facilitador;
use Support\Contracts\Manager\RelationshipableTrait;

class DatatableService
{
    use RelationshipableTrait;

    /**
     * Atributos
     */
    use GetSetTrait;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $actions;

    /**
     * Parejt
     *
     * @var          Illuminate\Database\Eloquent\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $dataType;

    /**
     * Parejt
     *
     * @var          Illuminate\Database\Eloquent\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $results;

    /**
     * Parejt
     *
     * @var          bool
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $isModelTranslatable;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $search;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $orderBy;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $orderColumn;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $sortOrder;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $searchNames;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $isServerSide;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $defaultSearchKey;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $usesSoftDeletes;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $showSoftDeleted;

    /**
     * Parejt
     *
     * @var          Illuminate\Support\Collection
     * @getter       true
     * @setter       true
     * @serializable true
     */
    protected $showCheckboxColum;
    protected $request;
    protected $getter;
    protected $repositoryService;
    protected $model;

    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
        $this->dataType = $repositoryService->getModelService()->getModelDataType();
        $this->request = App::make(Request::class);
    }

    public function getModelService()
    {
        return $this->repositoryService->getModelService();
    }

    public function getModelDataType()
    {
        return $this->dataType;
    }

    public static function make($collection)
    {
        return self::makeFromCollection($collection);
    }

    public static function makeFromCollection($collection)
    {
        // dd($collection);
        $model = new self(new ModelService(get_class($collection->first())));
        $model->setResults($collection);
        return $model;
    }

    /**
     * 
     */
    public function repositoryCreate($request = false)
    {

        $dataType = $this->getModelDataType();

        // Check permission
        // $this->authorize('add', app($dataType->model_name));

        $dataTypeContent = (strlen($dataType->model_name) != 0)
                            ? new $dataType->model_name()
                            : false;

        foreach ($dataType->addRows as $key => $row) {
            $dataType->addRows[$key]['col_width'] = $row->details->width ?? 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'add');

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);


        return [
            $dataType,
            $dataTypeContent,
            $isModelTranslatable
        ];
    }

    /**
     * 
     */
    public function repositoryIndex()
    {
        $this->getter = $this->dataType->server_side ? 'paginate' : 'get';

        $this->search = $this->search = (object) ['value' => $this->request->get('s'), 'key' => $this->request->get('key'), 'filter' => $this->request->get('filter')];

        $this->searchNames = [];
        if ($this->dataType->server_side) {
            $searchable = SchemaManager::describeTable(app($this->dataType->model_name)->getTable())->pluck('name')->toArray();
            $dataRow = Support::model('DataRow')->whereDataTypeId($this->dataType->id)->get();
            foreach ($searchable as $key => $value) {
                $displayName = $dataRow->where('field', $value)->first()->getTranslatedAttribute('display_name');
                $this->searchNames[$value] = $displayName ?: ucwords(str_replace('_', ' ', $value));
            }
        }

        $this->orderBy = $this->request->get('order_by', $this->dataType->order_column);
        $sortOrder = $this->request->get('sort_order', null);
        $usesSoftDeletes = false;
        $showSoftDeleted = false;
    
        // Replace relationships' keys for labels and create READ links if a slug is provided.
        if (!$this->results) {
            $this->results = $this->getDataTypeContent();
        }

        // Check if BREAD is Translatable
        if (($isModelTranslatable = is_bread_translatable($this->model))) {
            $this->results->load('translations');
        }

        // Check if server side pagination is enabled
        $isServerSide = isset($this->dataType->server_side) && $this->dataType->server_side;

        // Check if a default search key is set
        $defaultSearchKey = $this->dataType->default_search_key ?? null;

        // Actions
        $actions = [];
        if (!empty($this->results->first())) {
            foreach (Support::actions() as $action) {
                $action = new $action($this->dataType, $this->results->first());

                if ($action->shouldActionDisplayOnDataType()) {
                    $actions[] = $action;
                }
            }
        }

        // Define showCheckboxColumn
        $showCheckboxColumn = false;
        // @todo retirar comentario e so funcionar com usuario ativado 
        // if (Auth::user()->can('delete', app($this->dataType->model_name))) {
            $showCheckboxColumn = true;
        // } else {
        //     foreach ($actions as $action) {
        //         if (method_exists($action, 'massAction')) {
        //             $showCheckboxColumn = true;
        //         }
        //     }
        // }

        // Define orderColumn
        $orderColumn = [];
        if ($this->orderBy) {
            $index = $this->dataType->browseRows->where('field', $this->orderBy)->keys()->first() + ($showCheckboxColumn ? 1 : 0);
            $orderColumn = [[$index, 'desc']];
            if (!$sortOrder && isset($this->dataType->order_direction)) {
                $sortOrder = $this->dataType->order_direction;
                $orderColumn = [[$index, $this->dataType->order_direction]];
            } else {
                $orderColumn = [[$index, 'desc']];
            }
        }

        return [
            $actions,
            $this->dataType,
            $this->results,
            $isModelTranslatable,
            $this->search,
            $this->orderBy,
            $orderColumn,
            $sortOrder,
            $this->searchNames,
            $isServerSide,
            $defaultSearchKey,
            $usesSoftDeletes,
            $showSoftDeleted,
            $showCheckboxColumn
        ];
    }


    /**
     * Return default User Role.
     */
    public function getDataTypeContent()
    {
        // Next Get or Paginate the actual content from the MODEL that corresponds to the slug DataType
        if (strlen($this->dataType->model_name) != 0) {
            $this->model = app($this->dataType->model_name);

            if ($this->dataType->scope && $this->dataType->scope != '' && method_exists($this->model, 'scope'.ucfirst($this->dataType->scope))) {
                $query = $this->model->{$this->dataType->scope}();
            } else {
                $query = $this->model::select('*');
            }

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            // @todo retirar comentario e so funcionar com usuario ativado 
            // if ($this->model && in_array(SoftDeletes::class, class_uses($this->model)) && Auth::user()->can('delete', app($this->dataType->model_name))) {
                $usesSoftDeletes = true;

            if ($this->request->get('showSoftDeleted')) {
                $showSoftDeleted = true;
                $query = $query->withTrashed();
            }
            // }

            // If a column has a relationship associated with it, we do not want to show that field
            $this->removeRelationshipField($this->dataType, 'browse');

            if ($this->search->value != '' && $this->search->key && $this->search->filter) {
                $this->search_filter = ($this->search->filter == 'equals') ? '=' : 'LIKE';
                $this->search_value = ($this->search->filter == 'equals') ? $this->search->value : '%'.$this->search->value.'%';
                $query->where($this->search->key, $this->search_filter, $this->search_value);
            }

            if ($this->orderBy && in_array($this->orderBy, $this->dataType->fields())) {
                $querySortOrder = (!empty($sortOrder)) ? $sortOrder : 'desc';
                $this->dataTypeContent = call_user_func(
                    [
                    $query->orderBy($this->orderBy, $querySortOrder),
                    $this->getter,
                    ]
                );
            } elseif ($this->model->timestamps) {
                $this->dataTypeContent = call_user_func([$query->latest($this->model::CREATED_AT), $this->getter]);
            } else {
                $this->dataTypeContent = call_user_func([$query->orderBy($this->model->getKeyName(), 'DESC'), $this->getter]);
            }

            // Replace relationships' keys for labels and create READ links if a slug is provided.
            return $this->resolveRelations($this->dataTypeContent, $this->dataType);
        }

        $this->setModel(false);
        // If Model doesn't exist, get data from table name
        return call_user_func([DB::table($this->dataType->name), $this->getter]);
    }
}