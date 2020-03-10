<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WithdrawFundTransaction extends Model
{
    protected $fillable = [
        'id','reference_number','amount','initial_balance','balance','bank_id','user_id','status'
    ];
}
