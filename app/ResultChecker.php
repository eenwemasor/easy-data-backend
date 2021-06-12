<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultChecker extends Model
{
    protected $fillable = [
        'id','examination_body', 'price', 'product_code', 'description','vendor_price'
    ];
}
