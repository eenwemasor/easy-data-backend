<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CableSubscription extends Model
{
    protected $fillable = [
        'cable','plan','price'
    ];
}
