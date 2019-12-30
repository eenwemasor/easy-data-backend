<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirtimeTransaction extends Model
{
    protected $fillable = [
        'reference','phone','value','initial_balance','price','new_balance','status','method','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}






