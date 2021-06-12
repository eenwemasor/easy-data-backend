<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultCheckTransaction extends Model
{
    protected $fillable = [
        'id', 'reference', 'amount', 'initial_balance', 'new_balance','user_id','result_checker_id','status','wallet'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function result_checker()
    {
        return $this->belongsTo(ResultChecker::class);
    }

    public function pins()
    {
        return $this->hasMany(ResultCheckerPin::class);
    }
}
