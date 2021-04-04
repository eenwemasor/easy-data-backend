<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Bank extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'longcode',
        'gateway',
        'pay_with_bank',
        'active',
        'is_deleted',
        'country',
        'currency',
        'type',
    ];

    /**
     * @return BelongsToMany
     */
    public function bank_accounts(): BelongsToMany
    {
        return $this->belongsToMany(BankAccount::class);
    }
}
