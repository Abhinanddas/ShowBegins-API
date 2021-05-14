<?php

namespace App\Services;

use App\Models\Users as Users;

class LoginService
{

    public $userModel;
    public function __construct(Users $user){
        $this->userModel = $user;
    }

    public function getLoginErrorMessage($validatorObj)
    {
        $msg = '';
        foreach ($validatorObj->getMessages() as  $messages) {
            foreach ($messages as $message) {
                $msg .= $message.' ';
            }
        }
        return $msg;
    }

    public function login($email, $password){

        $query = $this->userModel->login($email,$password);
        if(!$query){
            return false;
        }
    }
}
