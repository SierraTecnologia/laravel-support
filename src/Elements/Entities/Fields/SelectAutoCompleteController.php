<?php

namespace Support\Elements\Entities\Fields;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\File;
use Facilitador\Http\Requests\FacilitadorRequest as NovaRequest;

class SelectAutoCompleteController extends Controller
{
    use ValidatesRequests;

    public function jsAsset(NovaRequest $request, $asset)
    {
        if (File::exists(__DIR__ . "/../dist/js/{$asset}.js")) {
            return response(File::get(__DIR__ . "/../dist/js/{$asset}.js"))
                ->header('Content-Type', 'application/javascript');
        }
        abort(404);
    }

    public function cssAsset(NovaRequest $request, $asset)
    {
        if (File::exists(__DIR__ . "/../dist/css/{$asset}.css")) {
            return response(File::get(__DIR__ . "/../dist/css/{$asset}.css"))
                ->header('Content-Type', 'text/css');
        }
        abort(404);
    }
}