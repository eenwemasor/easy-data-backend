<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    protected $fillable = [
        'direct_referrer_percentage',
        'indirect_referrer_percentage',
        'referee_percentage',
        'site_percentage',
        'direct_referrer_percentage_wallet_funding',
        'indirect_referrer_percentage_wallet_funding'
    ];
}
