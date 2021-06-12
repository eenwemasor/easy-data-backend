<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminChannelUtil extends Model
{
    protected $fillable = [
        'phone', 'email',
        'statement_request_charge',
        'glo_discount',
        'airtel_discount',
        'mtn_discount',
        'etisalat_discount',
        'paystack_transaction_fee',
        'paystack_fund_wallet_fee',
        'rave_fund_wallet_fee',
        'sms_unit_charge',
        'cable_tv_service_charge',
        'electricity_service_charge',
        'monnify_bank_service_charge',
        'fund_wallet_max_amount',
        'fund_wallet_min_amount'
    ];
}
