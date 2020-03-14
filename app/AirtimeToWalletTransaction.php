<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirtimeToWalletTransaction extends Model
{
    protected $fillable = [
        'id','reference','network','amount','sender_phone','recipient_phone','initial_balance','new_balance','user_id','status'
    ];
}
