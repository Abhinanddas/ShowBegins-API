<?php

namespace App\Services;

use App\Models\User as User;

class SignUpService
{

    public $userModel;
    public function __construct(User $user){
        $this->userModel = $user;
    }

    public function getSignUpErrorMessage($validatorObj)
    {
        $msg = '';
        foreach ($validatorObj->getMessages() as  $messages) {
            foreach ($messages as $message) {
                $msg .= $message.' ';
            }
        }
        return $msg;
    }

    public function signUpUser($params){

        $fields =[
            'email'=>$params['email'],
            'password'=>$this->hashPassword($params['password']),
            'name'=>$params['name'],
        ];

        $userId = $this->userModel->signUpUser($fields);
        return $userId;
    }

    public function hashPassword($password){
        return hash("md5",$password);
    }
}
