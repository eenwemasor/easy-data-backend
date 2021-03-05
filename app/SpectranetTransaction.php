<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectranetTransaction extends Model
{
    protected $fillable = [
        'id', 'reference', 'amount','initial_balance', 'new_balance','user_id','plan_id','status','wallet'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(SpectranetPriceList::class,'plan_id','id');
    }

    public function pins()
    {
        return $this->hasMany(SpectranetTransactionPin::class);
    }
}
