<?php

namespace App\Http\Middleware\Web;

use Closure;

class WebMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    const ALLOWED_URI = ['login'];

    public function handle($request, Closure $next)
    {
        if (in_array($request->route()->uri, self::ALLOWED_URI)) {
            return $next($request);
        }
        $session = $request->session()->has('access_token');
        if (!$session) {
            return redirect('/login');
        }
        return $next($request);
    }
}
