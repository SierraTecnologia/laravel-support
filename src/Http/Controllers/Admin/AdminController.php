<?php

namespace Support\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\ActiveUser;
use App\Models\Admin;
use App\Models\Banner;
use App\Models\HotTopic;
use App\Models\Link;
use Auth;
use Illuminate\Http\Request;
use MediaManager\Models\Image;
use Telefonica\Models\Actors;

class AdminController extends Controller
{
    public $controller;
    public $model = "";
    /**
     * Controller Class ou array
     */
    public $topBarParent = [
        'name' => 'Inicio',
        'url' => '/',
    ];

    public function title()
    {
    }
    
    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    public function index(Request $request)
    {
        return view(
            'layouts.app'
            // 'pedreiro::layouts.app'
            // compact('models', 'htmlGenerator')
        );
    }

    // /**
    //  * Display a listing of the resource searched.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function search()
    // {
    //     $registros = $this->facilitadorService->search($request->user()->id, $request->search);

    //     return view(
    //         'support::components.dash.search',
    //         compact('registros')
    //     );
    // }
}
