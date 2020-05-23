<?php

namespace Facilitador\Http\Controllers\System\Manager;

use Illuminate\Http\Request;
use Facilitador\Services\FacilitadorService;
use Population\Models\Components\Code\Commit;
use Facilitador\Services\RegisterService;
use Facilitador\Services\RepositoryService;
use Facilitador\Http\Requests\ModelUpdateRequest;
use Facilitador;

class RegisterController extends Controller
{
    protected $registerService;

    public function __construct(FacilitadorService $facilitadorService, RepositoryService $repositoryService, RegisterService $registerService)
    {
        $this->registerService = $registerService->load($repositoryService);
        parent::__construct($facilitadorService, $repositoryService);
    }


    //***************************************
    //                _____
    //               |  __ \
    //               | |__) |
    //               |  _  /
    //               | | \ \
    //               |_|  \_\
    //
    //  Read an item of our Data Type B(R)EAD
    //
    //****************************************

    public function index(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $service = $this->registerService;
        $modelRelationsResults = $service->getRelationsResults();
        
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();
        $id = $this->registerService->getId();
        $isSoftDeleted = false;

        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
            if ($dataTypeContent->deleted_at) {
                $isSoftDeleted = true;
            }
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where($this->registerService->getPrimaryKey(), $id)->first();
        }

        // Replace relationships' keys for labels and create READ links if a slug is provided.
        $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'read');

        // Check permission
        // $this->authorize('read', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'facilitador::cruds.bread.read';

        if (view()->exists("facilitador::cruds.$slug.read")) {
            $view = "facilitador::cruds.$slug.read";
        }

        // dd(
        //     $dataType,
        //     $dataTypeContent,
        //     $isModelTranslatable,
        //     $isSoftDeleted,
        // );

        return Facilitador::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'isSoftDeleted'));
    }

    //***************************************
    //                ______
    //               |  ____|
    //               | |__
    //               |  __|
    //               | |____
    //               |______|
    //
    //  Edit an item of our Data Type BR(E)AD
    //
    //****************************************

    public function edit(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $id = $this->registerService->getId();
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        // dd($dataType, $slug, $id);
        if (strlen($dataType->model_name) != 0) {
            $model = app($dataType->model_name);

            // Use withTrashed() if model uses SoftDeletes and if toggle is selected
            if ($model && in_array(SoftDeletes::class, class_uses($model))) {
                $model = $model->withTrashed();
            }
            if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
                $model = $model->{$dataType->scope}();
            }
            $dataTypeContent = call_user_func([$model, 'findOrFail'], $id);
        } else {
            // If Model doest exist, get data from table name
            $dataTypeContent = DB::table($dataType->name)->where($this->registerService->getPrimaryKey(), $id)->first();
        }

        foreach ($dataType->editRows as $key => $row) {
            $dataType->editRows[$key]['col_width'] = isset($row->details->width) ? $row->details->width : 100;
        }

        // If a column has a relationship associated with it, we do not want to show that field
        $this->removeRelationshipField($dataType, 'edit');

        // Check permission
        // $this->authorize('edit', $dataTypeContent);

        // Check if BREAD is Translatable
        $isModelTranslatable = is_bread_translatable($dataTypeContent);

        $view = 'facilitador::cruds.bread.edit-add';

        if (view()->exists("facilitador::cruds.$slug.edit-add")) {
            $view = "facilitador::cruds.$slug.edit-add";
        }

        return Facilitador::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable'));
    }

    // POST BR(E)AD
    public function update(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();
        $id = $this->registerService->getId();

        // Compatibility with Model binding.
        $id = $id instanceof \Illuminate\Database\Eloquent\Model ? $id->{$id->getKeyName()} : $id;

        $model = app($dataType->model_name);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        if ($model && in_array(SoftDeletes::class, class_uses($model))) {
            $data = $model->withTrashed()->findOrFail($id);
        } else {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);
        }

        // Check permission
        // $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id)->validate();
        $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

        event(new BreadDataUpdated($dataType, $data));

        if (auth()->user()->can('browse', $model)) {
            $redirect = redirect()->route("facilitador.{$dataType->slug}.index");
        } else {
            $redirect = redirect()->back();
        }

        return $redirect->with(
            [
            'message'    => __('facilitador::generic.successfully_updated')." {$dataType->getTranslatedAttribute('display_name_singular')}",
            'alert-type' => 'success',
            ]
        );
    }

    //***************************************
    //                _____
    //               |  __ \
    //               | |  | |
    //               | |  | |
    //               | |__| |
    //               |_____/
    //
    //         Delete an item BREA(D)
    //
    //****************************************

    public function destroy(Request $request)
    {
        $slug = $this->repositoryService->getSlug();

        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        // $this->authorize('delete', app($dataType->model_name));

        // Init array of IDs
        $ids = [];
        if (empty($id)) {
            // Bulk delete, get IDs from POST
            $ids = explode(',', $request->ids);
        } else {
            // Single item delete, get ID from URL
            $ids[] = $id;
        }
        foreach ($ids as $id) {
            $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

            $model = app($dataType->model_name);
            if (!($model && in_array(SoftDeletes::class, class_uses($model)))) {
                $this->cleanup($dataType, $data);
            }
        }

        $displayName = count($ids) > 1 ? $dataType->getTranslatedAttribute('display_name_plural') : $dataType->getTranslatedAttribute('display_name_singular');

        $res = $data->destroy($ids);
        $data = $res
            ? [
                'message'    => __('facilitador::generic.successfully_deleted')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('facilitador::generic.error_deleting')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataDeleted($dataType, $data));
        }

        return redirect()->route("facilitador.{$dataType->slug}.index")->with($data);
    }

    public function restore(Request $request)
    {
        $slug = $this->repositoryService->getSlug();
        $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();
        $id = $this->registerService->getId();

        // Check permission
        // $this->authorize('delete', app($dataType->model_name));

        // Get record
        $model = call_user_func([$dataType->model_name, 'withTrashed']);
        if ($dataType->scope && $dataType->scope != '' && method_exists($model, 'scope'.ucfirst($dataType->scope))) {
            $model = $model->{$dataType->scope}();
        }
        $data = $model->findOrFail($id);

        $displayName = $dataType->getTranslatedAttribute('display_name_singular');

        $res = $data->restore($id);
        $data = $res
            ? [
                'message'    => __('facilitador::generic.successfully_restored')." {$displayName}",
                'alert-type' => 'success',
            ]
            : [
                'message'    => __('facilitador::generic.error_restoring')." {$displayName}",
                'alert-type' => 'error',
            ];

        if ($res) {
            event(new BreadDataRestored($dataType, $data));
        }

        return redirect()->route("facilitador.{$dataType->slug}.index")->with($data);
    }

    //***************************************
    //
    //  Delete uploaded file
    //
    //****************************************

    public function remove_media(Request $request)
    {
        try {
            // GET THE SLUG, ex. 'posts', 'pages', etc.
            $slug = $request->get('slug');

            // GET file name
            $filename = $request->get('filename');

            // GET record id
            $id = $request->get($this->registerService->getPrimaryKey());

            // GET field name
            $field = $request->get('field');

            // GET multi value
            $multi = $request->get('multi');

            $dataType = Facilitador::model('DataType')->where('slug', '=', $slug)->first();

            // Load model and find record
            $model = app($dataType->model_name);
            $data = $model::find([$id])->first();

            // Check if field exists
            if (!isset($data->{$field})) {
                throw new Exception(__('facilitador::generic.field_does_not_exist'), 400);
            }

            // Check permission
            // $this->authorize('edit', $data);

            if (@json_decode($multi)) {
                // Check if valid json
                if (is_null(@json_decode($data->{$field}))) {
                    throw new Exception(__('facilitador::json.invalid'), 500);
                }

                // Decode field value
                $fieldData = @json_decode($data->{$field}, true);
                $key = null;

                // Check if we're dealing with a nested array for the case of multiple files
                if (is_array($fieldData[0])) {
                    foreach ($fieldData as $index=>$file) {
                        $file = array_flip($file);
                        if (array_key_exists($filename, $file)) {
                            $key = $index;
                            break;
                        }
                    }
                } else {
                    $key = array_search($filename, $fieldData);
                }

                // Check if file was found in array
                if (is_null($key) || $key === false) {
                    throw new Exception(__('facilitador::media.file_does_not_exist'), 400);
                }

                $fileToRemove = $fieldData[$key];

                // Remove file from array
                unset($fieldData[$key]);

                // Generate json and update field
                $data->{$field} = empty($fieldData) ? null : json_encode(array_values($fieldData));
            } else {
                if ($filename == $data->{$field}) {
                    $fileToRemove = $data->{$field};

                    $data->{$field} = null;
                } else {
                    throw new Exception(__('facilitador::media.file_does_not_exist'), 400);
                }
            }

            // Remove file from filesystem
            if ($fileToRemove != \Illuminate\Support\Facades\Config::get('sitec.facilitador.user.default_avatar')) {
                $this->deleteFileIfExists($fileToRemove);
            }

            $row = $dataType->rows->where('field', $field)->first();

            if (!empty($row->details->thumbnails)) {
                $ext = explode('.', $fileToRemove);
                $extension = '.'.$ext[count($ext) - 1];

                $path = str_replace($extension, '', $fileToRemove);

                foreach ($row->details->thumbnails as $thumbnail) {
                    $thumb_name = $thumbnail->name;

                    $this->deleteFileIfExists($path.'-'.$thumb_name.$extension);
                }
            }

            $data->save();

            return response()->json(
                [
                'data' => [
                   'status'  => 200,
                   'message' => __('facilitador::media.file_removed'),
                ],
                ]
            );
        } catch (Exception $e) {
            $code = 500;
            $message = __('facilitador::generic.internal_error');

            if ($e->getCode()) {
                $code = $e->getCode();
            }

            if ($e->getMessage()) {
                $message = $e->getMessage();
            }

            return response()->json(
                [
                'data' => [
                    'status'  => $code,
                    'message' => $message,
                ],
                ], $code
            );
        }
    }

}