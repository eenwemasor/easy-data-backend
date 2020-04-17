<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequestRegister extends Model
{
    protected $fillable = [
        'id','sender_id','receiver_id','amount','otp','expiry_date'
    ];
}
