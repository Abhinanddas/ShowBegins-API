<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Show extends Model
{
    protected $table = 'shows';

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'show_time' => 'datetime:d-m-Y h:i:s'
    ];

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
            throw new \Exception($e->getMessage());
        }
    }
}
