<?php

namespace Facilitador\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

/**
 * RepositoryService helper to make table and object form mapping easy.
 */
class RepositoryService
{

    protected $modelService;

    public function __construct(ModelService $modelClass)
    {
        $this->modelService = $modelClass;
    }

    public function getSlug()
    {
        return $this->getModelService()->getModelClass();
    }

    public function getModelService()
    {
        return $this->modelService;
    }

    public function getDiscoverService()
    {
        return $this->getModelService()->getDiscoverService();
    }

    public function getTableData()
    {
        return $this->getAll();
    }

    /**
     * Set the form maker connection.
     *
     * @param string $connection
     */
    public function getTableJson()
    {
        return DataTables::of($this->getModelService()->getModelQuery())->toJson();
    }


    public function count()
    {
        return $this->getModelService()->getModelClass()::count();
    }

    public function getAll()
    {
        return $this->getModelService()->getModelClass()::all();
    }

    public function search(Request $request)
    {
        $query = User::where('company_id', $request->input('company_id'));

        if ($request->has('last_name')) {
            $query->where('last_name', 'LIKE', '%' . $request->input('last_name') . '%');
        }

        if ($request->has('name')) {
            $query->where(
                function ($q) use ($request) {
                    return $q->where('first_name', 'LIKE', $request->input('name') . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $request->input('name') . '%');
                }
            );
        }

        $query->whereHas(
            'roles', function ($q) use ($request) {
                return $q->whereIn('id', $request->input('roles'));
            }
        )
            ->whereHas(
                'clients', function ($q) use ($request) {
                    return $q->whereHas('industry_id', $request->input('industry'));
                }
            );

        return $query->get();
    }

}
