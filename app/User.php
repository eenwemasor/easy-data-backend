<?php

namespace App;


use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'full_name',
        'email',
        'phone',
        'wallet',
        'transaction_pin',
        'accessibility',
        'account_level_id',
        'email_confirmed',
        'phone_verified',
        'unique_id',
        'active',
        'bonus_wallet',
        'password',
        'username',
        'referrer_id',
        'monnify_account_number',
        'monnify_bank_name',
        'monnify_bank_code',
        'monnify_collection_channel',
        'monnify_reservation_channel'
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

    /**
     * @return HasMany
     */
    public function data_transactions(): HasMany
    {
        return $this->hasMany(DataTransaction::class);
    }

    /**
     * @return HasMany
     */
    public function cable_transactions(): HasMany
    {
        return $this->hasMany(CableTransaction::class);
    }

    /**
     * @return HasMany
     */
    public function airtime_transactions(): HasMany
    {
        return $this->hasMany(AirtimeTransaction::class);
    }

    /**
     * @return HasMany
     */
    public function electricity_transactions(): HasMany
    {
        return $this->hasMany(ElectricityTransaction::class);
    }

    /**
     * @return HasMany
     */
    public function wallet_transactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    /**
     * @return HasMany
     */
    public function withdrawal_transactions(): HasMany
    {
        return $this->hasMany(WithdrawalTransaction::class);
    }


    /**
     * @return BelongsTo
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }

    /**
     * @return BelongsTo
     */
    public function account_level(): BelongsTo
    {
       return $this->belongsTo(AccountLevel::class, 'account_level_id','id');
    }

    /**
     * @return HasMany
     */
    public function banks(): HasMany
    {
        return $this->hasMany(BankAccount::class);
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
            $user->wallet_transactions()->each(function($transaction) {
                $transaction->delete();
            });
            $user->banks()->each(function($bank) {
                $bank->delete();
            });
            $user->withdrawal_transactions()->each(function($withdrawal) {
                $withdrawal->delete();
            });

        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    /**
     * @return bool
     */
    public function canWithdrawBonusWallet(): bool
    {
        return $this->account_level->bonus_wallet_withdrawal_minimum_balance < $this->bonus_wallet;

    }
}
