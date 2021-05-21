<?php

namespace App\Services\WebServices;

use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CommonWebService
{


    public static function fetchDataFromApi($url, $method, $params = [])
    {

        $request = Request::create('/api/' . $url, $method, $params);
        $request = self::setHeaders($request);
        $response = app()->handle($request);
        $statusCode = $response->getStatusCode();
        if ($statusCode != 200) {
            return false;
        }
        $responseContent = json_decode($response->getContent());
        return $responseContent;
    }

    public static function setHeaders($request)
    {

        $request->headers->set('Content-Type', 'application/json');
        $request->headers->set('accept', 'application/json');
        $request->headers->set('ShowBegins-APP-Key', env('APP_KEY'));
        $request->headers->set('ShowBegins-APP-Secret', env('APP_SECRET'));
        return $request;
    }
}
