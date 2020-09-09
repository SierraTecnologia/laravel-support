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
use Stalker\Models\Image;
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

    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    public function index()
    {
        return view(
            'layouts.app'
            // 'support::layouts.app'
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
