<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Show extends Model
{
    protected $table = 'shows';

    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function saveShow($data)
    {
        try {
            DB::table('shows')->insert($data);
            return true;
        } catch (\Exception $e) {
            dump($e);
            die;
            return false;
        }
    }
}
