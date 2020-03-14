<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\WalletTransactionStatus;
use App\WalletTransaction;
use Faker\Generator as Faker;

$factory->define(WalletTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        WalletTransactionStatus::SUCCESSFUL,
        WalletTransactionStatus::FAILED
    ];
    return [
        'reference'=>$faker->word,
        'transaction_type' => $faker->randomElement(["DEBIT", "CREDIT"]),
        'description' => $faker->randomElement(["Deposit", "Withdrawal", "Transfer", "Wallet Fund", "Airtime Purchase", "Data Purchase"]),
        'amount' => $faker->numberBetween(10000, 99999),
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'wallet' => $faker->randomElement(["WALLET", "BONUS_WALLET"]),
        'beneficiary' => $faker->phoneNumber,
        'status' => $faker->randomElement($transactionStatus),
        'user_id' => $faker->numberBetween(1, 3),
    ];
});
