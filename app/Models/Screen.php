<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Screen extends Model
{
    protected $table = 'screens';

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }


    public function saveScreen($data)
    {
        try {
            return $this->tableObject->insertGetId($data);
        } catch (\Exception $e) {
            return false;
        }
    }

    public function listAllScreens()
    {
        return $this->tableObject
            ->select('id', 'name','seating_capacity')
            ->where('is_deleted', false)
            ->orderBy('id','desc')
            ->get();
    }
}
