<?php

namespace App\Services\WebServices;

use App\Services\WebServices\CommonWebService as CommonWebService;

class LoginWebService
{

    public function login($email, $password)
    {

        $url = 'login';
        $params = ['email' => $email, 'password' => $password];
        $response = CommonWebService::fetchDataFromApi($url, 'POST', $params);
    }

    public function checkApiStatus()
    {
        $url = 'api-status';
        $response = CommonWebService::fetchDataFromApi($url, 'GET');
    }
}
