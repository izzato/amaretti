<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotOpenToPublic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest() && config('app.open_to_public') === false && $request->route()->uri !== '/' && $request->route()->uri !== 'login' && strpos($request->route()->uri, 'password') !== 0 && $request->method() === 'GET' && !request()->is('proposals*') && \Cookie::get('testing_app') === null) {
            return redirect('/');
        }

        return $next($request);
    }
}
