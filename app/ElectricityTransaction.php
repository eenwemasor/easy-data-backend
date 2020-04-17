<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElectricityTransaction extends Model
{
    protected $fillable = [
        'reference','meter_number','beneficiary_name','plan','type','initial_balance','amount','new_balance','status','method','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
