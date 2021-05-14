<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Users extends Eloquent
{
    protected $table = 'users';
    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function login($email, $password)
    {
        return $this->tableObject
            ->select('id')
            ->where('email', $email)
            ->where('password', $password)
            ->first();
    }
}
