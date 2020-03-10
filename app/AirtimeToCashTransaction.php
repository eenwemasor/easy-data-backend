<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AirtimeToCashTransaction extends Model
{
    protected $fillable = [
        'id','reference_number','network','amount','sender_phone','recipient_phone','initial_balance','balance','user_id','status'
    ];
}
