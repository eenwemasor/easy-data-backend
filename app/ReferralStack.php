<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReferralStack extends Model
{
    protected $fillable = [
        'referred_by','referred_to','status'
    ];
}
