<?php

namespace App\Services;

use App\Models\User as User;
use App\Services\CommonService as CommonService;

class SignUpService
{
    private $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
    }

    public function signUpUser($params)
    {

        $fields = [
            'email' => $params['email'],
            'password' => CommonService::hashPassword($params['password']),
            'name' => $params['name'],
        ];

        $userId = $this->userModel->signUpUser($fields);
        return $userId;
    }
}
