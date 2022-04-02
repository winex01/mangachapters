<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdvancedFileManager
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
        if (
            auth()->check() && 
            $request->is('elfinder') && 
            hasNoAuthority('advanced_file_manager')
        ) {
            abort(403);
        }

        return $next($request);
    }
}
