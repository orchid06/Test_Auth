<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Authorization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::guard('web')->user()->is_active) {
            Auth::guard('web')->logout();
            return redirect()->route('user.login')->withErrors([
                'email' => 'Your account is inactive.',
            ]);
        } 

        return $next($request);

    }
}
