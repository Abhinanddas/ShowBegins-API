<?php

namespace App\Services;


class CommonService
{

    public static function getErrorMessagesFromValidator($validatorObj)
    {
        $msg = '';
        foreach ($validatorObj->getMessages() as  $messages) {
            foreach ($messages as $message) {
                $msg .= $message . ' ';
            }
        }
        return $msg;
    }

    public static function hashPassword($password)
    {
        return hash("md5", $password);
    }

    public function calculatePercentage($total, $whole){
        return $total *($whole/100);
    }

}
