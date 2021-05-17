<?php

namespace App\Services;


class CommonService
{

    public $userModel;
    public function __construct()
    {
    }

    public function getErrorMessagesFromValidator($validatorObj)
    {
        $msg = '';
        foreach ($validatorObj->getMessages() as  $messages) {
            foreach ($messages as $message) {
                $msg .= $message . ' ';
            }
        }
        return $msg;
    }

    public function hashPassword($password)
    {
        return hash("md5", $password);
    }
}
