<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickBuy extends Model
{
    protected $fillable = ['reference', 'transaction_type', 'network', 'meter_number', 'decoder', 'phone', 'decoder_number', 'beneficiary_name', 'data', 'amount', 'beneficiary', 'plan', 'electricity_type', 'email', 'status'];
}







