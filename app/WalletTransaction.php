<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'transaction_type','description','amount', 'initial_balance', 'balance','wallet','beneficiary','status','user_id'
    ];
}
