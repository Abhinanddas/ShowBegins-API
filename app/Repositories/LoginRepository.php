<?php

namespace App\Repositories;

use App\Models\User;

class LoginRepository
{

    public function login($email, $password)
    {
        return User::select('id', 'email', 'name', 'mobile_no')
            ->where('email', $email)
            ->where('password', $password)
            ->first();
    }


    public function updateAccessTokens($userId, $tokenData)
    {
        return  User::where('id', $userId)
            ->update([
                'access_token' => $tokenData['access_token'],
                'refresh_token' => $tokenData['refresh_token'],
                'token_expires_at' => $tokenData['expires_at'],
            ]);
    }
}
