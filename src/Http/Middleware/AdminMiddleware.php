<?php

namespace Support\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('app.debug')) {
            return $next($request); //@todo
        }

        // Keep the guard selected by Laravel's application configuration. The
        // old FacilitadorGuard override could replace the restored web
        // session after the `auth` middleware had already authenticated it.
        $guard = config('auth.defaults.guard', 'web');

        if (! Auth::guard($guard)->guest()) {
            $user = Auth::guard($guard)->user();
            app()->setLocale($user->locale ?? app()->getLocale());
            return  $next($request); //@todo
            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        $urlLogin = route('login');

        return redirect()->guest($urlLogin);
    }
}
