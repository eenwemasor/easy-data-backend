<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminChannelUtil extends Model
{
    protected $fillable = [
        'phone','email',
        'statement_request_charge',
        'glo_discount',
        'airtel_discount',
        'mtn_discount',
        'etisalat_discount',
        'paystack_transaction_fee',
        'sms_unit_charge'
    ];
}
