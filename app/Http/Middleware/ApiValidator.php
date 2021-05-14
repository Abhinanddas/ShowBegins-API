<?php

namespace App\Http\Middleware;

use Closure;

class ApiValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $appKey = $request->header('showbegins-app-key');
        $appSecret = $request->header('showbegins-app-secret');

        if (($appKey == env('APP_KEY')) && ($appSecret == env('APP_SECRET'))) {
            return $next($request);
        }
        return response()->json(['status' => 'error', 'msg' => 'Unauthorised Access!'], 401);
    }
}
