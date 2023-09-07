<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class User extends Model
{
    protected $table = 'users';

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function signUpUser($data)
    {
        try
        {
            return $this->tableObject->insertGetId($data);
        }
        catch (\Exception $e)
        {
            return false;
        }
    }

    public static function findUserByAccessToken($accessToken)
    {
        return DB::table('users')
            ->where('access_token', $accessToken)
            ->first();
    }

    public function logout($userId)
    {

        return $this->tableObject
            ->where('id', $userId)
            ->update([
                'access_token' => null,
                'refresh_token' => null,
                'token_expires_at' => null,
            ]);
    }
}
