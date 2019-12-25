<?php

namespace App;


use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name', 'email', 'phone', 'wallet','accessibility','email_confirmed','phone_verified','unique_id','active','bonus_wallet','password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function data_transactions(){
        return $this->hasMany(DataTransaction::class);
    }

    public function cable_transactions(){
        return $this->hasMany(CableTransaction::class);
    }

    public function airtime_transactions(){
        return $this->hasMany(AirtimeTransaction::class);
    }
    public function electricity_transactions(){
        return $this->hasMany(ElectricityTransaction::class);
    }


    public static function boot() {
        parent::boot();
        self::deleting(function($user) {
            $user->data_transactions()->each(function($data){
                $data->delete();
            });
            $user->cable_transactions()->each(function($cable) {
                $cable->delete();
            });
            $user->airtime_transactions()->each(function($airtime) {
                $airtime->delete();
            });
            $user->electricity_transactions()->each(function($electricity) {
                $electricity->delete();
            });
        });
    }
}
