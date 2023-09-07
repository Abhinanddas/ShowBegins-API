<?php

namespace App\Http\Middleware;

use App\Http\Helper;
use Closure;
use App\Services\LoginService as LoginService;

class SessionValidator
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
        $bearerToken = $request->header('access_token');
        if (!$bearerToken)
        {
            return Helper::prettyApiResponse(status: 'error', message: trans('messages.access_token_missing'), statusCode: 401);
        }
        $accessToken = substr($bearerToken, 7);
        $user = LoginService::findUserByAccessToken($accessToken);

        if (!$user)
        {
            return Helper::prettyApiResponse(status: 'error', message: trans('messages.invalid_access_token'), statusCode: 401);
        }

        $request->session()->put('user', ['id' => $user->id, 'name' => $user->name, 'email' => $user->email, 'mobile_no' => $user->mobile_no]);
        return $next($request);
    }
}
