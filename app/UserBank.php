<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBank extends Model
{
    protected $fillable = [
        'id','name','bank_name','bank_number','user_id'
    ];


    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

}
