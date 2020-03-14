<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitcoinTransaction extends Model
{
    protected $fillable = [
        'id','reference','amount_to_sell','amount_to_receive','initial_balance','new_balance','buying_rate','user_id','status'
    ];
}
