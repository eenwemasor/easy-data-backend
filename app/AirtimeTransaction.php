<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirtimeTransaction extends Model
{
    protected $fillable = [
        'reference','phone','initial_balance','amount','new_balance','status','method','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}







