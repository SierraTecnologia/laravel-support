<?php

namespace Muleta\Modules\Eloquents\Displays;

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

abstract class RepositoryAbstract implements RepositoryInterface
{

    private $app;

    protected $model;

    /*
        Constructor
    */ 

    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    abstract function model();

    /*
    Get all boards associated with the user
    */

    public function all()
    {
        return $this->model->all();
    }

    public function get($id)
    {
        return $this->find($id);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        return $this->model->where('id', '=', $id)->update($data);
    }

    public function delete($board)
    {
        return $this->model->delete($board);
    }

    
    /**
     * Returns all paginated Strategies.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function paginated()
    {
        if (isset(request()->dir) && isset(request()->field)) {
            $model = $this->model->orderBy(request()->field, request()->dir);
        } else {
            $model = $this->model->orderBy('created_at', 'desc');
        }

        return $model->paginate(\Illuminate\Support\Facades\Config::get('siravel.pagination', 25));
    }

    /**
     * @return Model|string
     *
     * @psalm-return 'error'|Model
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());
        if(!$model instanceof Model) {
            // Throw a a repository exception
            dd($model, 'nao deveria cair aqui');
            return 'error';
        }

        return $this->model = $model;
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

}
