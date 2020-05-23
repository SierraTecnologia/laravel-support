<?php

namespace Facilitador\Http\Controllers\System\Manager;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Support\Components\Database\Schema\SchemaManager;
use Facilitador\Events\BreadDataAdded;
use Facilitador\Events\BreadDataDeleted;
use Facilitador\Events\BreadDataRestored;
use Facilitador\Events\BreadDataUpdated;
use Facilitador\Events\BreadImagesDeleted;
use Facilitador\Facades\Facilitador;


use Facilitador\Traits\Controllers\RepositoryTrait;

class RepositoryController extends Controller
{
    use RepositoryTrait;

    //***************************************
    //               ____
    //              |  _ \
    //              | |_) |
    //              |  _ <
    //              | |_) |
    //              |____/
    //
    //      Browse our Data Type (B)READ
    //
    //****************************************

    public function index(Request $request)
    {
        // GET THE SLUG, ex. 'posts', 'pages', etc.
        $slug = $this->repositoryService->getSlug();

        // GET THE DataType based on the slug
        if (!is_object($dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first())) {
            throw new Exception;
        }

        // // Check permission
        // $this->authorize('browse', app($dataType->model_name));

        list(
            $actions,
            $dataType,
            $dataTypeContent,
            $isModelTranslatable,
            $search,
            $orderBy,
            $orderColumn,
            $sortOrder,
            $searchNames,
            $isServerSide,
            $defaultSearchKey,
            $usesSoftDeletes,
            $showSoftDeleted,
            $showCheckboxColumn,
        ) = $this->repositoryIndex($dataType);


        $view = 'facilitador::cruds.bread.browse';

        if (view()->exists("facilitador::cruds.$slug.browse")) {
            $view = "facilitador::cruds.$slug.browse";
        }

        return Facilitador::view(
            $view, compact(
                'actions',
                'dataType',
                'dataTypeContent',
                'isModelTranslatable',
                'search',
                'orderBy',
                'orderColumn',
                'sortOrder',
                'searchNames',
                'isServerSide',
                'defaultSearchKey',
                'usesSoftDeletes',
                'showSoftDeleted',
                'showCheckboxColumn'
            )
        );
    }

    //***************************************
    //
    //                   /\
    //                  /  \
    //                 / /\ \
    //                / ____ \
    //               /_/    \_\
    //
    //
    // Add a new item of our Data Type BRE(A)D
    //
    //****************************************

    public function create(Request $request)
    {
        $slug = $this->repositoryService->getSlug();

        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

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

        $view = 'facilitador::cruds.bread.edit-add';

        if (view()->exists("facilitador::cruds.$slug.edit-add")) {
            $view = "facilitador::cruds.$slug.edit-add";
        }

        return Facilitador::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->repositoryService->getSlug();

        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        // $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows)->validate();
        $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

        event(new BreadDataAdded($dataType, $data));

        if (!$request->has('_tagging')) {
            if (auth()->user()->can('browse', $data)) {
                $redirect = redirect()->route("facilitador.{$dataType->slug}.index");
            } else {
                $redirect = redirect()->back();
            }

            return $redirect->with(
                [
                    'message'    => __('facilitador::generic.successfully_added_new')." {$dataType->getTranslatedAttribute('display_name_singular')}",
                    'alert-type' => 'success',
                ]
            );
        } else {
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    /**
     * Remove translations, images and files related to a BREAD item.
     *
     * @param \Illuminate\Database\Eloquent\Model $dataType
     * @param \Illuminate\Database\Eloquent\Model $data
     *
     * @return void
     */
    protected function cleanup($dataType, $data)
    {
        // Delete Translations, if present
        if (is_bread_translatable($data)) {
            $data->deleteAttributeTranslations($data->getTranslatableAttributes());
        }

        // Delete Images
        $this->deleteBreadImages($data, $dataType->deleteRows->where('type', 'image'));

        // Delete Files
        foreach ($dataType->deleteRows->where('type', 'file') as $row) {
            if (isset($data->{$row->field})) {
                foreach (json_decode($data->{$row->field}) as $file) {
                    $this->deleteFileIfExists($file->download_link);
                }
            }
        }

        // Delete media-picker files
        $dataType->rows->where('type', 'media_picker')->where('details.delete_files', true)->each(
            function ($row) use ($data) {
                $content = $data->{$row->field};
                if (isset($content)) {
                    if (!is_array($content)) {
                        $content = json_decode($content);
                    }
                    if (is_array($content)) {
                        foreach ($content as $file) {
                            $this->deleteFileIfExists($file);
                        }
                    } else {
                        $this->deleteFileIfExists($content);
                    }
                }
            }
        );
    }

    /**
     * Delete all images related to a BREAD item.
     *
     * @param \Illuminate\Database\Eloquent\Model $data
     * @param \Illuminate\Database\Eloquent\Model $rows
     *
     * @return void
     */
    public function deleteBreadImages($data, $rows)
    {
        foreach ($rows as $row) {
            if ($data->{$row->field} != \Illuminate\Support\Facades\Config::get('sitec.facilitador.user.default_avatar')) {
                $this->deleteFileIfExists($data->{$row->field});
            }

            if (isset($row->details->thumbnails)) {
                foreach ($row->details->thumbnails as $thumbnail) {
                    $ext = explode('.', $data->{$row->field});
                    $extension = '.'.$ext[count($ext) - 1];

                    $path = str_replace($extension, '', $data->{$row->field});

                    $thumb_name = $thumbnail->name;

                    $this->deleteFileIfExists($path.'-'.$thumb_name.$extension);
                }
            }
        }

        if ($rows->count() > 0) {
            event(new BreadImagesDeleted($data, $rows));
        }
    }

    /**
     * Order BREAD items.
     *
     * @param string $table
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function order(Request $request)
    {
        $slug = $this->repositoryService->getSlug();

        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        // $this->authorize('edit', app($dataType->model_name));

        if (!isset($dataType->order_column) || !isset($dataType->order_display_column)) {
            return redirect()
                ->route("facilitador.{$dataType->slug}.index")
                ->with(
                    [
                    'message'    => __('facilitador::cruds.bread.ordering_not_set'),
                    'alert-type' => 'error',
                    ]
                );
        }

        $model = app($dataType->model_name);
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $model = $model->withTrashed();
        }
        $results = $model->orderBy($dataType->order_column, $dataType->order_direction)->get();

        $display_column = $dataType->order_display_column;

        $dataRow = Facilitador::model('DataRow')->whereDataTypeId($dataType->id)->whereField($display_column)->first();

        $view = 'facilitador::cruds.bread.order';

        if (view()->exists("facilitador::cruds.$slug.order")) {
            $view = "facilitador::cruds.$slug.order";
        }

        return Facilitador::view(
            $view, compact(
                'dataType',
                'display_column',
                'dataRow',
                'results'
            )
        );
    }

    public function update_order(Request $request)
    {
        $slug = $this->repositoryService->getSlug();

        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        // $this->authorize('edit', app($dataType->model_name));

        $model = app($dataType->model_name);

        $order = json_decode($request->input('order'));
        $column = $dataType->order_column;
        foreach ($order as $key => $item) {
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $i = $model->withTrashed()->findOrFail($item->id);
            } else {
                $i = $model->findOrFail($item->id);
            }
            $i->$column = ($key + 1);
            $i->save();
        }
    }

    public function action(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        $action = new $request->action($dataType, null);

        return $action->massAction(explode(',', $request->ids), $request->headers->get('referer'));
    }

    /**
     * Get BREAD relations data.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function relation(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $page = $request->input('page');
        $on_page = 50;
        $search = $request->input('search', false);
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        $rows = $request->input('method', 'add') == 'add' ? $dataType->addRows : $dataType->editRows;
        foreach ($rows as $key => $row) {
            if ($row->field === $request->input('type')) {
                $options = $row->details;
                $skip = $on_page * ($page - 1);

                // If search query, use LIKE to filter results depending on field label
                if ($search) {
                    $total_count = app($options->model)->where($options->label, 'LIKE', '%'.$search.'%')->count();
                    $relationshipOptions = app($options->model)->take($on_page)->skip($skip)
                        ->where($options->label, 'LIKE', '%'.$search.'%')
                        ->get();
                } else {
                    $total_count = app($options->model)->count();
                    $relationshipOptions = app($options->model)->take($on_page)->skip($skip)->get();
                }

                $results = [];

                if (!$row->required && !$search) {
                    $results[] = [
                        'id'   => '',
                        'text' => __('facilitador::generic.none'),
                    ];
                }

                foreach ($relationshipOptions as $relationshipOption) {
                    $results[] = [
                        'id'   => $relationshipOption->{$options->key},
                        'text' => $relationshipOption->{$options->label},
                    ];
                }

                return response()->json(
                    [
                    'results'    => $results,
                    'pagination' => [
                        'more' => ($total_count > ($skip + $on_page)),
                    ],
                    ]
                );
            }
        }

        // No result found, return empty array
        return response()->json([], 404);
    }
}
