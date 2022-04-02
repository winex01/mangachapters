<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdvancedSetting
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
            $request->is('setting') && 
            hasNoAuthority('advanced_settings')
        ) {
            abort(403);
        }
        
        return $next($request);
    }
}
