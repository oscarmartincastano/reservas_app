<?php

namespace App\Http\Middleware;

use App\Models\Instalacion;
use Closure;
use Auth;

class CheckInstalacion
{
    public function handle($request, Closure $next)
    {
        $instalacion = Instalacion::where('slug', $request->slug_instalacion)->first();
        if ($instalacion) {
            return $next($request);
        }
        abort(404);
    }
}
?>