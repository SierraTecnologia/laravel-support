<?php

namespace Support\Http\Controllers\Painel;

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

class PainelController extends Controller
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
    public function index(Request $request)
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
    // public function search(Request $request)
    // {
    //     $registros = $this->facilitadorService->search($request->user()->id, $request->search);

    //     return view(
    //         'support::components.dash.search',
    //         compact('registros')
    //     );
    // }
}
