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

    public function listActiveMovies()
    {
        return $this->tableObject
            ->select('id', 'name')
            ->where('is_deleted', false)
            ->where('is_active', true)
            ->get();
    }
}
