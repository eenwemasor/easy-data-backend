<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'reference','transaction_type','description','amount', 'initial_balance', 'new_balance','wallet','beneficiary','status','user_id'
    ];
}
