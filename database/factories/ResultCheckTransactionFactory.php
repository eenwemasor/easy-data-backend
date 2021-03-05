<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use App\ResultCheckTransaction;
use Faker\Generator as Faker;

$factory->define(ResultCheckTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        TransactionStatus::SENT,
        TransactionStatus::COMPLETED,
        TransactionStatus::PROCESSING,
        TransactionStatus::FAILED,
    ];
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'amount' => $faker->numberBetween(100, 10000),
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'user_id' => $faker->numberBetween(1, 5),
        'result_checker_id' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement($transactionStatus),
        'wallet' => $faker->randomElement([WalletType::BONUS_WALLET, WalletType::WALLET])
    ];
});
