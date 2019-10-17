<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataPriceListing extends Model
{
    protected $fillable = [
        'network','plan','price'
    ];
}
