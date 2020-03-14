<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AirtimeTransaction;
use App\Enums\TransactionStatus;
use Faker\Generator as Faker;

$factory->define(/**
 * @param Faker $faker
 * @return array
 */ AirtimeTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        TransactionStatus::SENT,
        TransactionStatus::COMPLETED,
        TransactionStatus::PROCESSING,
        TransactionStatus::FAILED,
    ];
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'phone' => $faker->phoneNumber,
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'amount' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement($transactionStatus),
        'method'=> $faker->randomElement(["WALLET", "BONUS_WALLET"]),
        'user_id'=> $faker->numberBetween(1,5),
    ];
});
