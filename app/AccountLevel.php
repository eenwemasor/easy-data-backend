<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountLevel extends Model
{
    protected $fillable = [
        'name',
        'cost_to_upgrade'
    ];

    public function users()
    {
        return  $this->hasMany(User::class);
    }

    public function applicables()
    {
        return $this->hasMany(AccountLevelApplicable::class);
    }
}
