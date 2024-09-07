<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HasType
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
        if($request->user()->hasType())
            return $next($request);
        abort(403, "Votre compte n'a pas encore été confirmé");
    }
}
