<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CablePlanList extends Model
{
    protected $fillable = [
        'cable','plan','amount','product_code','vendor_identifier'
    ];
}
