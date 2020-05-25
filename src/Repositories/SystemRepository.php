<?php

namespace Support\Repositories;

use Carbon\Carbon;
use Support\Models\App\System;

class SystemRepository
{
    public $model;

    public function __construct(System $system)
    {
        $this->model = $system;
    }

    /**
     * Find by URL
     *
     * @param string $url
     * @param string $type
     *
     * @return Object|null
     */
    public function findByType($type)
    {
        $item = $this->model->where('code', $type)->first();

        if ($item) {
            // if ($item && ($item->data->is_published == 1 || $item->data->is_published == 'on') && $item->data->published_at <= Carbon::now(\Illuminate\Support\Facades\Config::get('app.timezone'))->format('Y-m-d H:i:s')) {
            return $item->data;
        }

        return null;
    }

}
