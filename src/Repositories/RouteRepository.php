<?php

namespace Support\Repositories;

use Carbon\Carbon;
use Support\Models\Application\Router;

class RouteRepository
{
    public $model;

    public function __construct(Router $routeEntity)
    {
        $this->model = $routeEntity;
    }

    /**
     * Find by URL
     *
     * @param string $url
     * @param string $type
     *
     * @return Object|null
     */
    public function findByRoute($route)
    {
        $item = collect($this->model->all());
        if (!$find = $item->search(function ($item, $key) use ($route) {
            return $item->uri() == $route;
        })) {
            return false;
        }
        
        return $item[$find];

        return null;
    }


}
