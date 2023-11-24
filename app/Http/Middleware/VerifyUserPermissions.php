<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissionkeys): Response
    {
		foreach($permissionkeys as $key) {
			if($request->user()->hasPermissions($key)) {
				return $next($request);
			}
		}
		abort(403);
    }
}
