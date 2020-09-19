<?php

namespace Support\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Log;
use Sentry\State\Scope;
use Support\Models\RedirectRule;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public static $DEFAULT_MESSAGE = 'Algo que não esta certo deu errado! Por favor, entre em contato conosco.';

    public $reportSendToSentry = false;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * Report or log an exception.
     *
     * @param  \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (config('app.env')=='production' && $this->shouldReport($exception)) {
            // Slack Report
            Log::channel('slack')->error('['.config('app.name').' (V'.config('app.version').') Report] Fatal erro: '.$exception->getMessage());

            if (app()->bound('sentry')) {
                // Sentry Report
                try {
                    \Sentry\configureScope(
                        function (Scope $scope): void {
                            if ($user = auth()->user()) {
                                $scope->setUser(
                                    [
                                    'id' => $user->id,
                                    'email' => $user->email,
                                    'cpf' => $user->cpf
                                    ]
                                );
                            }
                        }
                    );
                } catch (\Throwable $th) {
                    //throw $th;
                }
                app('sentry')->captureException($exception);
                $this->reportSendToSentry = true;
            }
        }
    
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Throwable               $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Check for custom handling
        if ($response = $this->handle404s($request, $exception)) {
            return $response;
        }

        if ($response = $this->handleCSRF($exception)) {
            return $response;
        }

        if ($response = $this->handleValidation($request, $exception)) {
            return $response;
        }

        if ($exception instanceof ValidationException && $request->expectsJson()) {
            return response()->json(['message' => 'The given data was invalid.', 'errors' => $exception->validator->getMessageBag()], 422);
        }

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException/* && $request->wantsJson()*/) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage()
                ],
                406
            );
        }

        if ($request->ajax() || $request->wantsJson()) {
            $json = [
                'success' => false,
                'message' => $exception->getMessage(),
                'obs'     => 'handlerByAjaxWantsJson',
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];
            if (config('app.env')=='production') {
                $json = [
                    'success' => false,
                    'message' => $exception->getMessage()
                ];
            }
            return response()->json($json, 400);
        }
        
        // Convert all non-http exceptions to a proper 500 http exception
        // if we don't do this exceptions are shown as a default template
        // instead of our own view in resources/views/errors/500.blade.php
        if (config('app.env')=='production' && $this->shouldReport($exception) && !$this->isHttpException($exception) && !config('app.debug')) {
            $exception = new HttpException(500, 'Whoops!');
        } elseif (config('app.debug') && $this->shouldReport($exception)) {
            dd('Error Handler', $exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * If a 404 exception, check if there is a redirect rule.  Or return a simple
     * header if an AJAX request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $e
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handle404s($request, $e)
    {
        // Check for right exception
        if (!is_a($e, ModelNotFoundException::class) && !is_a($e, NotFoundHttpException::class)) {
            return;
        }

        // Check for a valid redirect
        if ($rule = RedirectRule::matchUsingRequest()->first()) {
            return redirect($rule->to, $rule->code);
        }

        // Return header only on AJAX
        if ($request->ajax()) {
            return response(null, 404);
        }

        // Check for right exception
        if (is_a($e, NotFoundHttpException::class)) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Página não existe'
                ],
                406
            );
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Registro não encontrado'
            ],
            406
        );
    }

    /**
     * Colocado para mostrar a mensagem de exception normalmente.
     * Caso discordemos de alguma por favor alterar
     */
    private function getErrorMessage($exception)
    {
        if (config('app.env')=='production') {
            return self::$DEFAULT_MESSAGE;
        }
        Log::info('[Payment] Enviando para o cliente a mensagem: '.$exception->getMessage());
        return $exception->getMessage();
    }

    /**
     * If a CSRF invalid exception, log the user out
     *
     * @param  \Exception $e
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleCSRF($e)
    {
        if (!is_a($e, TokenMismatchException::class)) {
            return;
        }

        return app('facilitador.acl_fail');
    }

    /**
     * Redirect users to the previous page with validation errors
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\Response
     */
    protected function handleValidation($request, $exception)
    {
        if (!is_a($exception, ValidationFail::class)) {
            return;
        }

        // Log validation errors so Reporter will output them
        // if (Config::get('app.debug')) Log::debug(print_r($exception->validation->messages(), true));

        // Respond
        if ($request->ajax() || $request->wantsJson()) {
            // return response()->json($exception->validation->messages(), 400);
            return response()->json(
                [
                    'message' => 'Os dados fornecidos não são válidos.',
                    'errors' => $exception->validator->getMessageBag()
                ],
                422
            );
        }

        return back()->withInput()->withErrors($exception->validation);
    }
}
