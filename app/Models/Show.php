<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Show extends Model
{
    protected $table = 'shows';

    protected $dates = ['show_time'];

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function saveShow($data)
    {
        try {
            $this->tableObject->insert($data);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getAllActiveShows($fromShowTime)
    {
        return $this->tableObject
            ->select('shows.id as show_id', 'movies.id as movie_id', 'movies.name as movie_name', 'screens.name as screen_name', 'shows.show_time as show_time', 'shows.screen_id as screen_id')
            ->where('screens.is_deleted', false)
            // ->where('shows.show_time', '>=', $fromShowTime)
            ->leftJoin('movies', 'shows.movie_id', '=', 'movies.id')
            ->leftJoin('screens', 'shows.screen_id', '=', 'screens.id')
            ->orderBy('movie_id', 'desc')
            ->orderBy('show_time', 'asc')
            ->orderBy('screen_id', 'asc')
            ->get();
    }
}
