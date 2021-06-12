<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Helper;
use Exception;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';


    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function store($data)
    {
        try {
            return $this->tableObject->insertGetId($data);
        } catch (Exception $e) {
            return false;
        }
    }
}
