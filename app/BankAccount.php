<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankAccount extends Model
{
    protected $fillable = [
        'id','name','bank_id','bank_number', 'user_id'
    ];


    /**
     * @return BelongsToMany
     */
    public function withdrawal_transaction(): BelongsToMany
    {
        return $this->belongsToMany(WithdrawalTransaction::class);
    }

    /**
     * @return HasOne
     */
    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'id','bank_id');

    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
