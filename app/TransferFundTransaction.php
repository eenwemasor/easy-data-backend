<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransferFundTransaction extends Model
{
    protected $fillable = [
        'id','reference','description','amount','initial_balance','new_balance','recipient_id','user_id','status'
    ];


    public function recipient(){
        return $this->hasOne(User::class,'id','recipient_id');
    }

}
