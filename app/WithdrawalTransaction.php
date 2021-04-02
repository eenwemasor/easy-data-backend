<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WithdrawalTransaction extends Model
{
   protected  $fillable = ['reference',
       'initial_balance',
       'amount',
       'new_balance',
       'status',
       'method',
       'bank_id',
       'user_id',];

    /**
     * @return HasOne
     */
    public function bank(): HasOne
    {
        return $this->hasOne(BankAccount::class, 'bank_id','id');
    }
}
