<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralReward extends Model
{
    protected $fillable = [
        'referrer_percentage','indirect_referrer_percentage','referee_percentage','widget'
    ];
}
