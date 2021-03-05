<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResultCheckerPin extends Model
{
    protected $fillable = [
        'id', 'serial_number','pin','result_check_transaction_id'
    ];

    public function result_check_transaction()
    {
        return $this->belongsTo(ResultCheckTransaction::class);
    }
}
