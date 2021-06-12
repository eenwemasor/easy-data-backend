<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataTransaction extends Model
{
    protected $fillable = [
        'reference','network','data','initial_balance','amount','beneficiary','new_balance','status','method','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function data_plan(){
        return $this->hasOne(CablePlanList::class, 'id', 'data');
    }
}






