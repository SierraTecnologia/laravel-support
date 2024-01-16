<?php

namespace SiUtils\Exceptions;

use Throwable;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Illuminate\Auth\AuthenticationException;
use Sentry\State\Scope;
use function Sentry\captureException;
use function Sentry\configureScope;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    protected $sendToSlack = false;

    public static $DEFAULT_MESSAGE = 'Algo que não esta certo deu errado! Por favor, entre em contato conosco.';
    public static $IGNORE_MESSAGES = [
        'Array to string conversion'
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * A list of the exception types that should not be reported to log.
     *
     * @var array
     */
    protected $dontReport = [
        /**
         * From Laravel
         */
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Tymon\JWTAuth\Exceptions\TokenExpiredException::class,
        JWTException::class,

        /**
         * Eu add
         */
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    protected $httpCodes = [
        0   => 'Unknown error',
        // [Informational 1xx]
        100 => 'Continue',
        101 => 'Switching Protocols',
        // [Successful 2xx]
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        // [Redirection 3xx]
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        // [Client Error 4xx]
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        // [Server Error 5xx]
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (config('app.env') !== 'production') {
            dd('HandlerMuleta', $exception);
        }
        if (\Illuminate\Support\Facades\Config::get('app.env')=='production' && app()->bound('sentry') && $this->shouldReport($exception)) {
            // Slack Report
            Log::channel('slack')->error('[PaymentService Fatal Error] Fatal erro: '.$exception->getMessage());

            // Slack Report
            if ($user = auth()->user()) {
                Log::channel('slack')->error(
                    '[Passepague Api] Usuário: '.$user->cpf.'('.$user->email.')'.
                    ' Fatal erro: '.$exception->getMessage()
                );
            } else {
                Log::channel('slack')->error('[Passepague Api] Fatal erro: '.$exception->getMessage());
            }

            // Sentry Report
            // \Sentry\configureScope(function (Scope $scope): void {
            //     if ($user = auth()->user()) {
            //         $scope->setUser([
            //             'id' => $user->id,
            //             'email' => $user->email,
            //             'cpf' => $user->cpf
            //         ]);
            //     }
            // });
            app('sentry')->captureException($exception);
        }
    
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Handle notify exceptions which will redirect to the
        // specified location then show a notification message.
        if ($this->isExceptionType($exception, NotifyException::class)) {
            session()->flash('error', $this->getOriginalMessage($exception));
            return redirect($exception->redirectLocation);
        }

        // Handle pretty exceptions which will show a friendly application-fitting page
        // Which will include the basic message to point the user roughly to the cause.
        if ($this->isExceptionType($exception, PrettyException::class)  && !\Illuminate\Support\Facades\Config::get('app.debug')) {
            $message = $this->getOriginalMessage($exception);
            $code = ($exception->getCode() === 0) ? 500 : $exception->getCode();
            return response()->view('errors/' . $code, ['message' => $message], $code);
        }

        // Handle 404 errors with a loaded session to enable showing user-specific information
        if ($this->isExceptionType($exception, NotFoundHttpException::class)) {
            return \Route::respondWithRoute('fallback');
        }



        if ($exception instanceof TokenMismatchException) {
            // Redirect to a form. Here is an example of how I handle mine
            return redirect($request->fullUrl())->with('csrf_error', "Oops! Seems you couldn't submit form for a long time. Please try again.");
        }
        
        // if ($exception instanceof ModelNotFoundException/* && $request->wantsJson()*/) {
        //     return response()->json(
        //         [
        //             'success' => false,
        //             'message' => 'Registro não encontrado'
        //         ],
        //         404
        //     );
        // }

        // if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException/* && $request->wantsJson()*/) {
        //     return response()->json(
        //         [
        //             'success' => false,
        //             'message' => $exception->getMessage()
        //         ],
        //         404
        //     );
        // }

        // if ($request->ajax() || $request->wantsJson())
        // {
        //     $json = [
        //         'success' => false,
        //         'message' => $exception->getMessage(),
        //         'obs'     => 'handlerByAjaxWantsJson',
        //         'error' => [
        //             'code' => $exception->getCode(),
        //             'message' => $exception->getMessage(),
        //         ],
        //     ];
        //     Log::notice('Respondendo com resposta entrada. '.print_r(debug_backtrace(), true));
        //     return response()->json($json, 400);
        // }
        
        // // Convert all non-http exceptions to a proper 500 http exception
        // // if we don't do this exceptions are shown as a default template
        // // instead of our own view in resources/views/errors/500.blade.php
        // if ($this->shouldReport($exception) && !$this->isHttpException($exception) && !\Illuminate\Support\Facades\Config::get('app.debug')) {
        //     $exception = new HttpException('Whoops!', 500);
        // }

        // return parent::render($request, $exception);

        
        // If debug is enabled in local environment dump stack trace
        if(\Illuminate\Support\Facades\Config::get('app.debug') and app()->environment('local')) {
            return (class_exists('Whoops\\Run')) ? $this->whoops($exception) : parent::render($request, $e);
        }

        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        }

        // HTTP exceptions are are normally intentionally thrown and its safe to show their message
        if($this->isHttpException($exception)) {
            $code = $exception->getStatusCode();
            $message = $exception->getMessage();

            if(empty($message)) {
                $message = (isset($this->httpCodes[$code])) ? $this->httpCodes[$code] : $this->httpCodes[500];
            }
        }
        // Other exceptions are usually unexpected errors and is best not to show their message but instead disguise them as error 500.
        else
        {
            $code = $exception->getCode();

            if(! isset($this->httpCodes[$code])) {
                $code = 500;
            }

            $message = $this->httpCodes[$code];
        }

        // If a custom view exist use it, otherwise use generic error page
        $view = (view()->exists("errors/$code")) ? "errors/$code" : 'layouts/error';

        // Data for the view
        $data = [
        'title' => $message,
        'code'  => $code
        ];

        return response()->view($view, $data, $code);
    }

    /**
     * Check the exception chain to compare against the original exception type.
     *
     * @param  Throwable $e // Troquei de Exception p/ Throwable
     * @param  $type
     * @return bool
     */
    protected function isExceptionType(Throwable $e, $type)
    {
        do {
            if (is_a($e, $type)) {
                return true;
            }
        } while ($e = $e->getPrevious());
        return false;
    }

    /**
     * Get original exception message.
     *
     * @param  Throwable $e // Troquei de Exception p/ Throwable
     * @return string
     */
    protected function getOriginalMessage(Throwable $e)
    {
        do {
            $message = $e->getMessage();
        } while ($e = $e->getPrevious());
        return $message;
    }


    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request                   $request
     * @param  \Illuminate\Validation\ValidationException $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, \Illuminate\Validation\ValidationException $exception)
    {
        return response()->json($exception->errors(), $exception->status);
    }

    /**
     * Render an exception into an HTTP response using Whoops.
     *
     * @return \Illuminate\Http\Response
     */
    protected function whoops(Throwable $e)
    {
        $whoops = new \Whoops\Run;
        $handler = new \Whoops\Handler\PrettyPageHandler;
        $handler->setEditor('sublime');
        $whoops->pushHandler($handler);

        return response($whoops->handleException($e), $e->getStatusCode(), $e->getHeaders());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request                 $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, \Illuminate\Auth\AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    /**
     * Colocado para mostrar a mensagem de exception normalmente.
     * Caso discordemos de alguma por favor alterar
     */
    private function getErrorMessage($exception)
    {
        if (
            config('app.env')=='production' &&
            in_array($exception->getMessage(), self::$IGNORE_MESSAGES)
        ){
            if (!$this->sendToSlack) {
                Log::channel('slack')->info(
                    'Enviando para o cliente mensagem de erro padrão. Por causa de: '.
                    $exception->getMessage()
                );
                $this->sendToSlack = true;
            }
            return self::$DEFAULT_MESSAGE;
        }
        if (!$this->sendToSlack) {
            Log::channel('slack')->info(
                'Enviando para o cliente a mensagem: '.$exception->getMessage()
            );
            $this->sendToSlack = true;
        }
        return $exception->getMessage();
    }
}
