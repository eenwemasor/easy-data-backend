<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiftcardTransaction extends Model
{
    protected $fillable = [
        'id','reference_number','gift_card_type','amount_to_sell','amount_to_receive','initial_balance','balance','user_id','status'
    ];
}
