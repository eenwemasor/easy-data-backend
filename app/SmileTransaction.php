<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SmileTransaction extends Model
{
    protected $fillable = [
        'id', 'reference', 'amount', 'smart_card_number','beneficiary_name','transaction_type','initial_balance','new_balance','user_id','plan_id','status','method'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SmilePriceList::class,'plan_id','id');
    }
}
