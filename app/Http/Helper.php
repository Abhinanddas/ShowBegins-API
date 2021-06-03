<?php

namespace App\Http;

class Helper
{

    public static function prettyApiResponse($messge, $status = 'success', $data = [], $statusCode = 200)
    {   
        return response()->json(['status' => $status, 'msg' => $messge, 'data' => $data], $statusCode);
    }
}
