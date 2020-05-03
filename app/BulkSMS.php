<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkSMS extends Model
{
    protected $fillable = [
        'reference','sender_id','receivers','message','amount','status','user_id'
    ];
}



