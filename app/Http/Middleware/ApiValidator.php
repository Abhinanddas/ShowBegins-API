<?php

namespace App\Http\Middleware;

use App\Http\Helper;
use Closure;
use Illuminate\Support\Facades\Log;

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
        $appKey = $request->header('ShowBegins-APP-Key');
        $appSecret = $request->header('ShowBegins-APP-Secret');

        Log::info(env('APP_KEY'));
        if (($appKey == env('APP_KEY')) && ($appSecret == env('APP_SECRET')))
        {
            return $next($request);
        }

        return Helper::prettyApiResponse(status: 'error', message: trans('messages.app_key_missing'), statusCode: 401);
    }
}
