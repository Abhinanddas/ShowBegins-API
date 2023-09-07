<?php

namespace App\Http;

class Helper
{

    public static function prettyApiResponse($message, $status = 'success', $data = [], $statusCode = 200)
    {   
        return response()->json(['status' => $status, 'message' => $message, 'data' => $data], $statusCode);
    }
}
