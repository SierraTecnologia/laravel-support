<?php 

namespace Muleta\Packagist\Traits;

use Auth;
use Socialite;
use Illuminate\Http\Request;
use MediaManager\Models\User;
use Flash;

trait ResponseControllerTrait
{
    use PackageVersionTrait;

    /**
     * @SWG\Swagger(
     *   basePath="/api/v1",
     * @SWG\Info(
     *     title="Laravel Generator APIs",
     *     version="1.0.0",
     *   )
     * )
     * This class should be parent class for other API controllers
     * Class AppBaseController
     */
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
    
    /**
     * Other Jsons Response
     */

    /**
     * Response com Array
     * [success] {Bollean}
     */
    protected function defaultResponse($success=true)
    {
        return [
            'success' => $success
        ];
    }

    /**
     * Response com Array
     * [success] true
     * [message] {String}
     */
    protected function responseWithMessage($message)
    {
        $array = [
            'message' => $message
        ];
        return array_merge($this->defaultResponse(true), $array);
    }

    /**
     * Response com Array
     * [success] false
     * [message] {String}
     */
    protected function responseWithErrorMessage($message)
    {
        $array = [
            'message' => $message
        ];
        return array_merge($this->defaultResponse(false), $array);
    }

    /**
     * Response com Array
     * [success] false
     * [message] {String}
     */
    protected function responseWithErrors($validation)
    {
        $errors = $validation->messages();
        return $this->responseWithErrorMessage($errors[0]);
    }

    /**
     * Response com Array
     * [success] false
     * [data] {Array}
     */
    protected function responseWithData($data)
    {
        $array = [
            'data' => $data
        ];
        return array_merge($this->defaultResponse(true), $array);
    }
}
