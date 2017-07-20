<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Session
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() OR Auth::user()->type !== 'admin') {
            return redirect('/');
        }

        return $next($request);
    }
}