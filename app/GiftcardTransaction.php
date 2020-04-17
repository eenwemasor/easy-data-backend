<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftcardTransaction extends Model
{
    protected $fillable = [
        'id','reference','gift_card_type','buying_rate','amount_to_sell','amount_to_receive','user_id','status'
    ];
}
