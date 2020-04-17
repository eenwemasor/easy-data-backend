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
        'bitcoin_buying_rate',
        'giftcard_buying_rate',
        'data_pin',
        'paystack_transaction_fee',
        'rave_transaction_fee',
        'trade_airtime_recipient_number_glo',
        'trade_airtime_recipient_number_airtel',
        'trade_airtime_recipient_number_etisalat',
        'trade_airtime_recipient_number_mtn'
    ];
}
