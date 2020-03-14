<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferFundTransaction extends Model
{
    protected $fillable = [
        'id','reference','amount','initial_balance','new_balance','recipient_id','user_id','status'
    ];
}
