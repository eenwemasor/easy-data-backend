<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuickBuy extends Model
{
    protected $fillable = [
        'reference','transaction_type','network','plan','amount','beneficiary','email','status'
    ];
}







