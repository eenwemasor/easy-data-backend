<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectranetPriceList extends Model
{
    protected $fillable = [
        'description','product_code','vendor_price','prices'
    ];
}
