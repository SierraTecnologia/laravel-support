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
        auth()->setDefaultDriver(app('FacilitadorGuard'));
        // dd(config('auth.guards'), auth());

        // dd(Auth::user());
        if (!Auth::guest()) {
            $user = Auth::user();
            app()->setLocale($user->locale ?? app()->getLocale());
            return  $next($request); //@todo
            return $user->hasPermission('browse_admin') ? $next($request) : redirect('/');
        }

        $urlLogin = route('login');

        return redirect()->guest($urlLogin);
    }
}
