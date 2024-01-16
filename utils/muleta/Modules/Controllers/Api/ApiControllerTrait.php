<?php

namespace Muleta\Modules\Controllers\Api;

use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * Add functionality for exporting to CSV
 */
trait ApiControllerTrait
{
    public $_version = null;

    /**
     * Tenta capturar um token para o business via SERVER, POST, ou GET
     * Caso não ache ele usa o token padrão da passepague
     */
    public function getVersion()
    {
        if (!empty($this->_version)) {
            return $this->_version;
        }

        if(!empty($_SERVER['HTTP_VERSION'])) {
            Log::info('Usando Version: '.$_SERVER['HTTP_VERSION']);
            return User::where('token', $_SERVER['HTTP_VERSION'])->first();
        }
            
        if(!empty($_POST['version'])) {
            Log::info('Usando Version: '.$_POST['version']);
            return User::where('token', $_POST['version'])->first();
        }
        
        if(!empty($_POST['VERSION'])) {
            Log::info('Usando Version: '.$_POST['VERSION']);
            return User::where('token', $_POST['VERSION'])->first();
        }
        
        if(!empty($_GET['version'])) {
            Log::info('Usando Version: '.$_GET['version']);
            return User::where('token', $_GET['version'])->first();
        }
        
        if(!empty($_GET['VERSION'])) {
            Log::info('Usando Version: '.$_GET['VERSION']);
            return User::where('token', $_GET['VERSION'])->first();
        }
        
        return $this->_version = config('app.version');
    }

    /**
     * Response com Array
     * [success] {Bollean}
     *
     * @return array
     *
     * @psalm-return array{success: mixed}
     */
    protected function defaultResponse($success=true): array
    {
        return [
            'success' => $success
        ];
    }

    /**
     * Response com Array
     * [success] true
     * [message] {String}
     *
     * @return array
     */
    protected function responseWithMessage($message): array
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
     *
     * @return array
     */
    protected function responseWithErrorMessage($message): array
    {
        $array = [
            'message' => $message
        ];
        Log::info('[Payment] '.print_r($array, true).print_r($this->defaultResponse(false), true).print_r(array_merge($this->defaultResponse(false), $array), true));
        return array_merge($this->defaultResponse(false), $array);
    }

    /**
     * Response com Array
     * [success] false
     * [message] {String}
     *
     * @return array
     */
    protected function responseWithErrors($validation): array
    {
        $errors = $validation->messages();
        return $this->responseWithErrorMessage($errors[0]);
    }

    /**
     * Response com Array
     * [success] false
     * [data] {Array}
     *
     * @return array
     */
    protected function responseWithData($data): array
    {
        $array = [
            'data' => $data
        ];
        return array_merge($this->defaultResponse(true), $array);
    }

    
    public function sendResponse($result, $message)
    {
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }

    public function sendError($error, $code = 404)
    {
        return Response::json(ResponseUtil::makeError($error), $code);
    }
    /**
     * Generate an api response.
     *
     * @param string $type    Response type
     * @param string $message Response string
     *
     * @return Response
     */
    public function apiResponse($type, $message, $code = 200)
    {
        return Response::json(['status' => $type, 'data' => $message], $code);
    }

    /**
     * Generate an API error response.
     *
     * @param array $errors Validation errors
     * @param array $inputs Input values
     *
     * @return Response
     */
    public function apiErrorResponse($errors, $inputs)
    {
        $message = [];
        foreach ($inputs as $key => $value) {
            if (!isset($errors[$key])) {
                $message[$key] = [
                    'status' => 'valid',
                    'value' => $value,
                ];
            } else {
                $message[$key] = [
                    'status' => 'invalid',
                    'error' => $errors[$key],
                    'value' => $value,
                ];
            }
        }

        return Response::json(['status' => 'error', 'data' => $message]);
    }
}
