<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthInstalacion
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && auth()->user()->instalacion->slug == $request->slug_instalacion) {
            return $next($request);
        }
        Auth::logout();
        return redirect()->guest(route('login'));
    }
}
?>