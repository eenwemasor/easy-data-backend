<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountLevelApplicable extends Model
{
    protected $fillable = [
        'account_level_id',
        'service_type',
        'application_method',
        'calculation_method',
        'value'
    ];

    public function account_level()
    {
        return $this->belongsTo(AccountLevel::class, 'account_level_id', 'id');
    }
}
