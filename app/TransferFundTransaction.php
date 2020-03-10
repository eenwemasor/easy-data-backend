<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferFundTransaction extends Model
{
    protected $fillable = [
        'id','reference_number','amount','initial_balance','balance','recipient_id','user_id','status'
    ];
}
