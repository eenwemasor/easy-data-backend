<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawFundTransaction extends Model
{
    protected $fillable = [
        'id','reference','amount','initial_balance','new_balance','bank_id','user_id','status'
    ];
}
