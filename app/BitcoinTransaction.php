<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitcoinTransaction extends Model
{
    protected $fillable = [
        'id','reference','amount_to_sell','amount_to_receive','buying_rate','user_id','status'
    ];
}
