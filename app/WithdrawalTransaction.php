<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WithdrawalTransaction extends Model
{
   protected  $fillable = ['reference',
       'initial_balance',
       'amount',
       'new_balance',
       'transfer_code',
       'transfer_reference',
       'transfer_id',
       'description',
       'status',
       'method',
       'bank_id',
       'user_id',
       ];

    /**
     * @return HasOne
     */
    public function bankAccount(): HasOne
    {
        return $this->hasOne(BankAccount::class, 'id','bank_id');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
