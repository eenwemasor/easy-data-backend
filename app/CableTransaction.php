<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CableTransaction extends Model
{
    protected $fillable = [
        'reference','service','decoder_number','initial_balance','price','new_balance','status','method','user_id'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}








