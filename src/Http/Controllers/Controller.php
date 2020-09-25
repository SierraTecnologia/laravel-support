<?php

namespace Support\Http\Controllers;

use App;
use Bkwld\Library\Laravel\Validator as BkwldLibraryValidator;
use Bkwld\Library\Utils\File;
use Event;
use Facilitador;
use Former;
use Illuminate\Support\Str;
use Pedreiro\Elements\Fields\Listing;
use Pedreiro\Exceptions\ValidationFail;
use Pedreiro\Http\Controllers\Controller as BaseController;
use Pedreiro\Models\Base as BaseModel;
use Pedreiro\Template\Input\ModelValidator;
use Pedreiro\Template\Input\NestedModels;
use Pedreiro\Template\Input\Position;
use Pedreiro\Template\Input\Search;
use Pedreiro\Template\Input\Sidebar;
use Redirect;
use Request;
use Response;
use Route;
use SupportURL;
use Translation\Template\Localize;
use URL;
use Validator;
use View;
use stdClass;

class Controller extends BaseController
{
    protected function getFeature($model)
    {
        $isModels = [
            'page',
            'link',
            'menu',
            'plan',
        ];
        if (in_array($model, $isModels)) {
            return 'Negocios';
        }

        return 'System';
    }
}
