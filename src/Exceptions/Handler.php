<?php

namespace Support\Exceptions;

use Throwable;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sentry\State\Scope;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Support\Models\RedirectRule;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    public static $DEFAULT_MESSAGE = 'Algo que não esta certo deu errado! Por favor, entre em contato conosco.';

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (config('app.env')=='production'/* && app()->bound('sentry') && $this->shouldReport($exception)*/) {
            // Slack Report
            Log::channel('slack')->error('[PaymentService Fatal Error] Fatal erro: '.$exception->getMessage());

            // // Sentry Report
            // // \Sentry\configureScope(function (Scope $scope): void {
            // //     if ($user = auth()->user()) {
            // //         $scope->setUser([
            // //             'id' => $user->id,
            // //             'email' => $user->email,
            // //             'cpf' => $user->cpf
            // //         ]);
            // //     }
            // // });
            // app('sentry')->captureException($exception);
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
        if (config('app.env')!=='production' && config('app.debug')){
            dd($exception);
        }
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

        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException/* && $request->wantsJson()*/) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $exception->getMessage()
                ],
                406
            );
        }

        if ($request->ajax() || $request->wantsJson())
        {
            $json = [
                'success' => false,
                'message' => $exception->getMessage(),
                'obs'     => 'handlerByAjaxWantsJson',
                'error' => [
                    'code' => $exception->getCode(),
                    'message' => $exception->getMessage(),
                ],
            ];
            return response()->json($json, 400);
        }
        
        // Convert all non-http exceptions to a proper 500 http exception
        // if we don't do this exceptions are shown as a default template
        // instead of our own view in resources/views/errors/500.blade.php
        if (config('app.env')=='production' && $this->shouldReport($exception) && !$this->isHttpException($exception) && !config('app.debug')) {
            $exception = new HttpException(500, 'Whoops!');
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
        if (config('app.env')=='production'){
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
     * @param  \Exception               $e
     * @return \Illuminate\Http\Response
     */
    protected function handleValidation($request, $e)
    {
        if (!is_a($e, ValidationFail::class)) {
            return;
        }

        // Log validation errors so Reporter will output them
        // if (Config::get('app.debug')) Log::debug(print_r($e->validation->messages(), true));

        // Respond
        if ($request->ajax()) {
            return response()->json($e->validation->messages(), 400);
        }

        // return response()->json(
        //     [
        //         'message' => 'Os dados fornecidos não são válidos.',
        //         'errors' => $exception->validator->getMessageBag()
        //     ],
        //     422
        // );

        return back()->withInput()->withErrors($e->validation);
    }
}
