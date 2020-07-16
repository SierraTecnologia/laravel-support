<?php

namespace Support\Http\Controllers\Manipule;

use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class CreatorController extends Controller
{
    use ValidatesRequests;

    /**
     * @param Request         $request
     * @param ResponseFactory $response
     *
     * @return BinaryFileResponse
     */
    public function download(Request $request, ResponseFactory $response): BinaryFileResponse
    {
        $models = $database->convertToModels();
        $models->convertToMigrations();
    }
}
