<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawFundTransaction extends Model
{
    protected $fillable = [
        'id','reference','amount','initial_balance','new_balance','bank_id','user_id','status'
    ];


    public function receiving_bank(){
        return $this->hasOne(UserBank::class,'id','bank_id');
    }
}
