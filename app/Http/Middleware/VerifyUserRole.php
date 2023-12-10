<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$rolequery): Response
    {
		foreach($rolequery as $role) {
			if($request->user()->hasRole($role)) {
				return $next($request);
			}
		}
		abort(403);
    }
}
