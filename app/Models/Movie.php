<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Movie extends Model
{
    protected $table = 'movies';

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }


    public function saveMovie($data)
    {
        try {
            return $this->tableObject->insertGetId($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function listAllMovies()
    {
        return $this->tableObject
            ->select('id', 'name')
            ->where('is_deleted', false)
            ->get();
    }

    public function listActiveMovies()
    {
        return $this->tableObject
            ->select('id', 'name')
            ->where('is_deleted', false)
            ->where('is_active', true)
            ->get();
    }
}
