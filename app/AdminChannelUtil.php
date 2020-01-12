<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminChannelUtil extends Model
{
    protected $fillable = [
        'phone','email','account_activation_amount','glo_discount','airtel_discount','mtn_discount','etisalat_discount'
    ];
}
