<?php

namespace Support\Repositories;

use Carbon\Carbon;
use Translation\Repositories\TranslationRepository;
use Illuminate\Support\Facades\Schema;
use Support\Models\Application\DataType;

class DataTypeRepository
{
    public $translationRepo;

    public $model;

    public $table;

    public function __construct(DataType $supportEntity)
    {
        $this->model = $supportEntity;
    }

    /**
     * Returns all Widgets.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->model->orderBy('created_at', 'desc')->get()->all();
    }

    public function allWithCount()
    {
        $models = $this->all();
        $array = [];

        foreach ($models as $model) {
            try {
                if (class_exists($model->model_name)) {
                    $model = $model->getModelService();
                    $array[] = [
                        'model' => $model,
                        'url' => $model->getUrl(),
                        'count' => $model->getRepository()->count(),
                        'icon' => $model->getIcon(),
                        'name' => $model->getName(),
                        'group_package' => $model->getGroupPackage(),
                        'group_type' => $model->getGroupType(),
                        'history_type' => $model->getHistoryType(),
                        'register_type' => $model->getRegisterType(),
                    ];
                } else {
                    \Log::warning('Não existe classe: '.$model->model_name);
                }

            } catch (\Illuminate\Database\QueryException $e) {
                \Log::warning('DataTypeRepository Class Dont Exist: '.$e->getMessage());
            } catch(LogicException|ErrorException|RuntimeException|OutOfBoundsException|TypeError|ValidationException|FatalThrowableError|FatalErrorException|Exception|Throwable  $e) {
                $this->setErrors($e);
                // dd('a',
                //     $model->getEloquentEntity(),
                //     $model,
                //     $e
                // );
            } 
        }
        // dd('Modelos', $array);
        return collect($array);
    }

    public function allModelsServices()
    {
        $models = $this->all();
        $array = [];

        foreach ($models as $model) {
            $array[] = $model->getModelService();
        }

        return collect($array);
    }

    /**
     * Returns all publishedAndPaginated items.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function publishedAndPaginated()
    {
        return $this->paginated();
    }

    /**
     * Returns all paginated items.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(\Illuminate\Support\Facades\Config::get('cms.pagination', 25));
    }

    /**
     * Returns all published items.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function published()
    {
        return $this->model->where('is_published', 1)
            ->where('published_at', '<=', Carbon::now(\Illuminate\Support\Facades\Config::get('app.timezone'))->format('Y-m-d H:i:s'))
            ->orderBy('created_at', 'desc')
            ->paginate(\Illuminate\Support\Facades\Config::get('cms.pagination', 24));
    }

    /**
     * Returns all public items
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function arePublic()
    {
        if (Schema::hasColumn($this->model->getTable(), 'is_published')) {
            $query = $this->model->where('is_published', 1);

            if (Schema::hasColumn($this->model->getTable(), 'published_at')) {
                $query->where('published_at', '<=', Carbon::now(\Illuminate\Support\Facades\Config::get('app.timezone'))->format('Y-m-d H:i:s'));
            }

            return $query->orderBy('created_at', 'desc')->get();
        }

        return $this->model->orderBy('created_at', 'desc')->get();
    }

    /**
     * Search the columns of a given table
     *
     * @param array $payload
     *
     * @return array
     */
    public function search($payload)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$payload['term'].'%');

        $columns = Schema::getColumnListing($this->table);

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload['term'].'%');
        }

        return [$query, $payload['term'], $query->paginate(25)->render()];
    }

    /**
     * Stores Widgets into database.
     *
     * @param array $payload
     *
     * @return Widgets
     */
    public function store($payload)
    {
        return $this->model->create($payload);
    }

    /**
     * Find Widgets by given id.
     *
     * @param int $id
     *
     * @return \Illuminate\Support\Collection|null|static|Widgets
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Find items by slug.
     *
     * @param int $slug
     *
     * @return \Illuminate\Support\Collection|null|static|Model
     */
    public function getBySlug($slug)
    {
        return $this->model->where('slug', $slug)->first();
    }

    /**
     * Find items by url.
     *
     * @param int $url
     *
     * @return \Illuminate\Support\Collection|null|static|Model
     */
    public function getByUrl($url)
    {
        return $this->model->where('url', $url)->first();
    }

    /**
     * Updates items into database.
     *
     * @param Model $model
     * @param array $payload
     *
     * @return Model
     */
    public function update($model, $payload)
    {
        return $model->update($payload);
    }

    /**
     * Convert block payloads into json
     *
     * @param array  $payload
     * @param string $module
     *
     * @return array
     */
    public function parseBlocks($payload, $module)
    {
        $blockCollection = [];

        foreach ($payload as $key => $value) {
            if (stristr($key, 'block_')) {
                $blockName = str_replace('block_', '', $key);
                $blockCollection[$blockName] = $value;
                unset($payload[$key]);
            }
        }

        $blockCollection = $this->parseTemplate($payload, $blockCollection, $module);

        if (empty($blockCollection)) {
            $payload['blocks'] = "{}";
        } else {
            $payload['blocks'] = json_encode($blockCollection);
        }

        return $payload;
    }

    /**
     * Parse the template for blocks.
     *
     * @param array $payload
     * @param array $currentBlocks
     *
     * @return array
     */
    public function parseTemplate($payload, $currentBlocks, $module)
    {
        if (isset($payload['template'])) {
            $content = file_get_contents(base_path('resources/themes/'.\Illuminate\Support\Facades\Config::get('cms.frontend-theme').'/'.$module.'/'.$payload['template'].'.blade.php'));

            preg_match_all('/->block\((.*)\)/', $content, $pageMethodMatches);
            preg_match_all('/\@block\((.*)\)/', $content, $bladeMatches);

            $matches = array_unique(array_merge($pageMethodMatches[1], $bladeMatches[1]));

            foreach ($matches as $match) {
                $match = str_replace('"', "", $match);
                $match = str_replace("'", "", $match);
                if (!isset($currentBlocks[$match])) {
                    $currentBlocks[$match] = '';
                }
            }
        }

        return $currentBlocks;
    }
}
