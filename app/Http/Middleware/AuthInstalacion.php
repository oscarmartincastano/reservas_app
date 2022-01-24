<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthInstalacion
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && auth()->user()->instalacion->slug == $request->slug_instalacion) {
            if (auth()->user()->rol == 'admin') {
                return redirect(auth()->user()->instalacion->slug . '/admin');
            }
            return $next($request);
        }
        Auth::logout();
        return redirect()->guest(route('login'));
    }
}
?>