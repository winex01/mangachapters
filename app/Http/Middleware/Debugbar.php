<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Debugbar
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        \Debugbar::disable();

        if (auth()->check() && hasAuthority('admin_debugbar')) {
            \Debugbar::enable();
        }

        return $next($request);
    }
}
