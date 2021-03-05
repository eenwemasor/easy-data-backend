<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmilePriceList extends Model
{
    protected $fillable = [
        'description','product_code','vendor_price','price'
    ];
}
