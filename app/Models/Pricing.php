<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Http\Helper;

class Pricing extends Model
{
    protected $table = 'pricings';


    public function __construct()
    {
        $this->tableObject = $this->getConnectionResolver()->connection()->table($this->table);
    }

    public function store($data)
    {
        try {
            $this->tableObject->insert($data);
            return true;
        } catch (\Exception $e) {
            return Helper::prettyApiResponse(trans('messages.insert_failure', ['item' => 'Movie']), 'error');
        }
    }

    public function getAllPricing()
    {
        return $this->tableObject
            ->select('id as id', 'name as name', 'value as value',  'is_value_in_percentage as is_percentage', 'is_base_ticket_charge as is_base_price')
            ->where('is_deleted', false)
            ->where('is_active', true)
            ->orderBy('value', 'desc')
            ->get();
    }

    public function getPricing($isBasic = false)
    {
        return $this->tableObject
            ->select('id as id','name as name', 'value as value', 'is_value_in_percentage as is_percentage')
            ->where('is_base_ticket_charge', $isBasic)
            ->where('is_deleted', false)
            ->where('is_active', true)
            ->orderBy('value', 'desc')
            ->get();
    }
}
