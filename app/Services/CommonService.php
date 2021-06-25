<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

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

    public function calculatePercentage($total, $whole)
    {
        return $total * ($whole / 100);
    }

    public function checkIfDataExists($id, $tableName)
    {
        $query = DB::table($tableName)
            ->where('id', $id)
            ->where('is_deleted', false)
            ->first();

        return $query ? true : false;
    }
}
