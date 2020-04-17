<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\TransactionType;
use App\Enums\WalletTransactionStatus;
use App\Enums\WalletType;
use App\WalletTransaction;
use Faker\Generator as Faker;

$factory->define(WalletTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        WalletTransactionStatus::SUCCESSFUL,
        WalletTransactionStatus::FAILED
    ];
    $walletType = [
        WalletType::WALLET,
        WalletType::BONUS_WALLET
    ];
    $transactionType = [
        TransactionType::DEBIT,
        TransactionType::CREDIT
    ];
    return [
        'reference'=>$faker->word,
        'transaction_type' => $faker->randomElement($transactionType),
        'description' => $faker->sentence(15),
        'amount' => $faker->numberBetween(10000, 99999),
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'wallet' => $faker->randomElement($walletType),
        'beneficiary' => $faker->phoneNumber,
        'status' => $faker->randomElement($transactionStatus),
        'user_id' => $faker->numberBetween(1, 3),
    ];
});
