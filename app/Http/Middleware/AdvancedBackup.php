<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdvancedBackup
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
            $request->is('backup') && 
            hasNoAuthority('advanced_backups')
        ) {
            abort(403);
        }

        return $next($request);
    }
}
