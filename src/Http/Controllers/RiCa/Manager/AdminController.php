<?php

namespace Support\Http\Controllers\RiCa\Manager;

use App\Http\Requests;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\Link;
use App\Models\ActiveUser;
use App\Models\HotTopic;
use MediaManager\Models\Image;
use Illuminate\Http\Request;
use Auth;
use Telefonica\Models\Actors;

class AdminController extends Controller
{
    /**
     * Controller Class ou array
     */
    public $topBarParent = [
        'name' => 'Inicio',
        'url' => '/',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $models = $this->facilitadorService->getModelServicesToArray(false); //->sortByDesc('field', [], true);
        $dataTypeRepository = resolve(\Support\Repositories\DataTypeRepository::class);
        $models = $dataTypeRepository->allWithCount();

        // $importantModels = $models->reject(
        //     function ($item) use ($importantConfigModels) {
        //         $item->model->isModelClass
        //         return false;
        //         // return empty($item['count']);
        //     }
        // );
        // dd($models);

        $models = $models->reject(
            function ($item) {
                return false;
                // return empty($item['count']);
            }
        )->SortByDesc('count')
        ->groupBy('group_type');

        $htmlGenerator = new \Support\Generators\FacilitadorGenerator($this->facilitadorService);
        // dd($models, 'Debug AdminController');
        return view(
            'support::components.dash.home',
            compact('models', 'htmlGenerator')
        );
    }

    /**
     * Display a listing of the resource searched.
     *
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $registros = $this->facilitadorService->search($request->user()->id, $request->search);

        return view(
            'support::components.dash.search',
            compact('registros')
        );
    }
}
