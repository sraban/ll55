<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
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
        //dd($guard);guest:admin, guest:superadmin
        // This is Called all before login pages, if already login will redirect

        if (Auth::guard($guard)->check()) {

            if( Auth::guard($guard)->user()->id == 1 ) return redirect('/home');
            if( Auth::guard($guard)->user()->id == 2 ) return redirect('/admin');
        }

        return $next($request);
    }
}
