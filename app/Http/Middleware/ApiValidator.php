<?php

namespace App\Http\Middleware;

use App\Http\Helper;
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

        if (($appKey == env('APP_KEY')) && ($appSecret == env('APP_SECRET')))
        {
            return $next($request);
        }

        return Helper::prettyApiResponse(status: 'error', message: trans('messages.app_key_missing'), statusCode: 401);
    }
}
