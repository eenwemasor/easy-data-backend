<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BankAccount extends Model
{
    protected $fillable = [
        'id','name','bank_name','bank_number', 'user_id', "bank_list_id"
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
    public function bank_list(): HasOne
    {
        return $this->hasOne(BankList::class, 'bank_list_id','id');

    }

}
