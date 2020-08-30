<?php

namespace Support\Routing;

use App;
use Muleta\Traits\Providers\ConsoleTools;
use Request;
use Route;

/**
 * This class acts as a bootstrap for setting up
 * Facilitador routes
 */
class Router
{
    use ConsoleTools;
    
    /**
     * Action for current wildcard request
     *
     * @var string
     */
    private $action;

    /**
     * The path "directory" of the admin.  I.e. "admin"
     *
     * @var string
     */
    private $dir;

    /**
     * Constructor
     *
     * @param string $dir The path "directory" of the admin.  I.e. "admin"
     */
    public function __construct($dir)
    {
        $this->dir = $dir;
    }

    /**
     * Register all routes
     *
     * @return void
     */
    public function registerAll()
    {
        // $namespacePrefix = '\\'.\Illuminate\Support\Facades\Config::get('application.core.controllers.namespace');
        /**
         * Facilitador Routes
         */
        Route::group([
            'namespace' => '\Support\Http\Controllers', //$namespacePrefix, //
        ], function (/**$router**/) {
            require __DIR__.'/../../routes/web.php';
        });
        /**
         * Admin Routes
         */
        Route::group(
            [
                'namespace' => '\Support\Http\Controllers\Admin', //$namespacePrefix, //
                'middleware' => 'admin',
                'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.admin', 'admin'),
                'as' => 'admin.',
            ],
            function ($router) {
                if (file_exists(__DIR__.'/../../routes/admin.php')) {
                    include __DIR__.'/../../routes/admin.php';
                } else {
                    $this->loadRoutesFromPath(__DIR__.'/../../routes/admin');
                }
            }
        );
        /**
         * RiCa Routes
         */
        Route::group(
            [
                'namespace' => '\Support\Http\Controllers\RiCa', //$namespacePrefix, //
                'middleware' => 'admin',
                'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.rica', 'rica'),
                'as' => 'rica.',
            ],
            function ($router) {
                if (file_exists(__DIR__.'/../../routes/rica.php')) {
                    include __DIR__.'/../../routes/rica.php';
                } else {
                    $this->loadRoutesFromPath(__DIR__.'/../../routes/rica');
                }
            }
        );



        // Public routes
        Route::group([
            'prefix' => $this->dir,
            'middleware' => 'facilitador.public',
        ], function () {
            $this->registerLogin();
            $this->registerResetPassword();
        });

        // // Routes that don't require auth or CSRF
        // Route::group([
        //     'prefix' => $this->dir,
        //     'middleware' => 'facilitador.endpoint',
        // ], function () {
        //     $this->registerExternalEndpoints(); // @todo Stalkers
        // });

        // Protected, admin routes
        Route::group([
            'prefix' => $this->dir,
            'middleware' => 'facilitador.protected', //@todo voltar aqui
        ], function () {
            $this->registerAdmins();
            $this->registerElements();
            $this->registerRedactor();
            $this->registerWorkers();
            $this->registerWildcard(); // Must be last
        });
    }

    /**
     * Account routes
     *
     * @return void
     */
    public function registerLogin()
    {
        Route::get('/', [
            'as' => 'facilitador.account@login',
            'uses' => '\Facilitador\Http\Controllers\Auth\LoginController@showLoginForm',
        ]);

        Route::post('/', [
            'as' => 'facilitador.account@postLogin',
            'uses' => '\Facilitador\Http\Controllers\Auth\LoginController@login',
        ]);

        Route::get('logout', [
            'as' => 'facilitador.account@logout',
            'uses' => '\Facilitador\Http\Controllers\Auth\LoginController@logout',
        ]);

        /**
         * Facilitador Admin
         */
        Route::get('login', [
            'uses' => '\Facilitador\Http\Controllers\Auth\FacilitadorAuthController@login',
            'as' => 'rica.login'
        ]);
        Route::post('login', [
            'uses' => '\Facilitador\Http\Controllers\Auth\FacilitadorAuthController@postLogin',
            'as' => 'facilitador.postlogin'
        ]);
    }

    /**
     * Reset password routes
     *
     * @return void
     */
    public function registerResetPassword()
    {
        Route::get('forgot', ['as' => 'facilitador.account@forgot',
            'uses' => '\Facilitador\Http\Controllers\Auth\ForgotPasswordController@showLinkRequestForm',
        ]);

        Route::post('forgot', ['as' => 'facilitador.account@postForgot',
            'uses' => '\Facilitador\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail',
        ]);

        Route::get('password/reset/{code}', ['as' => 'facilitador.account@reset',
            'uses' => '\Facilitador\Http\Controllers\Auth\ResetPasswordController@showResetForm',
        ]);

        Route::post('password/reset/{code}', ['as' => 'facilitador.account@postReset',
            'uses' => '\Facilitador\Http\Controllers\Auth\ResetPasswordController@reset',
        ]);
    }

    /**
     * Setup wilcard routing
     *
     * @return void
     */
    public function registerWildcard()
    {
        Route::group([
            'prefix' => 'wildcard',
        ], function () {
            // Setup a wildcarded catch all route
            Route::any('{path}', ['as' => 'facilitador.wildcard', function ($path) {

                // Remember the detected route
                App::make('events')->listen('wildcard.detection', function ($controller, $action) {
                    $this->action($controller.'@'.$action);
                });

                // Do the detection
                $router = App::make('facilitador.wildcard');
                $response = $router->detectAndExecute();
                if (is_a($response, 'Symfony\Component\HttpFoundation\Response')
                    || is_a($response, 'Illuminate\View\View')) { // Possible when layout is involved
                    return $response;
                } else {
                    App::abort(404);
                }
            }])->where('path', '.*');
        });
    }

    /**
     * Non-wildcard admin routes
     *
     * @return void
     */
    public function registerAdmins()
    {
        Route::get('admins/{id}/disable', [
            'as' => 'facilitador.admins@disable',
            'uses' => '\Facilitador\Http\Controllers\Admin\Admins@disable',
        ]);

        Route::get('admins/{id}/enable', [
            'as' => 'facilitador.admins@enable',
            'uses' => '\Facilitador\Http\Controllers\Admin\Admins@enable',
        ]);
    }

    /**
     * Workers
     *
     * @return void
     */
    public function registerWorkers()
    {
        Route::get('workers', [
            'as' => 'facilitador.workers',
            'uses' => '\Facilitador\Http\Controllers\Admin\Workers@index',
        ]);

        Route::get('workers/tail/{worker}', [
            'as' => 'facilitador.workers@tail',
            'uses' => '\Facilitador\Http\Controllers\Admin\Workers@tail',
        ]);
    }


    /**
     * Elements system
     *
     * @return void
     */
    public function registerElements()
    {
        Route::get('elements/field/{key}', [
            'as' => 'facilitador.elements@field',
            'uses' => '\Facilitador\Http\Controllers\Admin\Elements@field',
        ]);

        Route::post('elements/field/{key}', [
            'as' => 'facilitador.elements@field-update',
            'uses' => '\Facilitador\Http\Controllers\Admin\Elements@fieldUpdate',
        ]);

        Route::get('elements/{locale?}/{tab?}', [
            'as' => 'facilitador.elements',
            'uses' => '\Facilitador\Http\Controllers\Admin\Elements@index',
        ]);

        Route::post('elements/{locale?}/{tab?}', [
            'as' => 'facilitador.elements@store',
            'uses' => '\Facilitador\Http\Controllers\Admin\Elements@store',
        ]);
    }

    /**
     * Upload handling for Redactor
     * @link http://imperavi.com/redactor/
     *
     * @return void
     */
    public function registerRedactor()
    {
        Route::post('redactor', '\Facilitador\Http\Controllers\Admin\Redactor@store');
    }
    
    public function getRouteSpace()
    {
        $req = explode('/', Request::path());

        $first = array_shift($req);

        if ($first == \Illuminate\Support\Facades\Config::get('application.routes.rica', 'rica')) {
            return 'rica';
        }

        if ($first == \Illuminate\Support\Facades\Config::get('application.routes.admin', 'admin')) {
            return 'admin';
        }

        if ($first == \Illuminate\Support\Facades\Config::get('application.routes.painel', 'painel')) {
            return 'admin';
        }

        return 'main';
    }

    /**
     * Set and get the action for this request
     *
     * @return string '\Facilitador\Http\Controllers\Admin\Account@forgot'
     */
    public function action($name = null)
    {
        if ($name) {
            $this->action = $name;
        }

        if ($this->action) {
            return $this->action;
        }

        // Wildcard
        return Route::currentRouteAction();
    }
}
