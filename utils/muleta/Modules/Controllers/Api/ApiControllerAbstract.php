<?php

namespace Muleta\Modules\Controllers\Api;

use Request;
use Schema;
use Config;
use Illuminate\Routing\Controller as BaseController;

abstract class ApiControllerAbstract extends BaseController
{
    use ApiControllerTrait;

    protected $model;

    protected $modelOrderAttribute = 'created_at';
    protected $modelOrderDirection = 'desc';

    public function __construct(Request $request)
    {
        // parent::__construct();

        // $url = $request->segment(3) ?? 'page';

        // $this->model = str_singular($url);

        // if (! empty($this->model)) {
        //     $this->model = app('Tramite\Models\\'.$this->getFeature($this->model).'\\'.ucfirst($this->model));
        // }
    }

    /**
     * Find an item in the API
     *
     * @param int $id
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Collect all items of a resource
     *
     * @return Collection
     */
    public function all()
    {
        $query = $this->model;

        if (Schema::hasColumn(str_plural($this->model), 'is_published')) {
            $query = $query->where('is_published', true);
        }

        if (Schema::hasColumn(str_plural($this->model), 'published_at')) {
            $query = $query->where('published_at', '<=', Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s'));
        }

        if (Schema::hasColumn(str_plural($this->model), 'finished_at')) {
            $query = $query->where('finished_at', '>=', Carbon::now(config('app.timezone'))->format('Y-m-d H:i:s'));
        }

        $objects = $query
            ->orderBy($this->modelOrderAttribute, $this->modelOrderDirection)
            ->paginate(Config::get('siravel.pagination', 24));
        return new $this->modelCollection($objects);
    }

    /**
     * Search for the API Item
     *
     * @param string $term
     *
     * @return array
     */
    public function search($term)
    {
        $query = $this->model->orderBy($this->modelOrderAttribute, $this->modelOrderDirection);
        $query->where('id', 'LIKE', '%'.$input['term'].'%');

        $columns = Schema::getColumnListing(str_plural($this->model));

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$input['term'].'%');
        }

        return [
            'term' => $input['term'],
            'result' => $query->paginate(Config::get('siravel.pagination', 24)),
        ];
    }
}
