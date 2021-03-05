<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpectranetTransactionPin extends Model
{
    protected $fillable = [
        'serial_number','pin','value','spectranet_transaction_id'
    ];

    public function spectranet_transaction()
    {
        return $this->belongsTo(SpectranetTransaction::class);
    }
}
