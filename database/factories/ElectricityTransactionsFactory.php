<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\ElectricityTransaction;
use App\Enums\ElectricityType;
use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use Faker\Generator as Faker;

$factory->define(ElectricityTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        TransactionStatus::SENT,
        TransactionStatus::COMPLETED,
        TransactionStatus::PROCESSING,
        TransactionStatus::FAILED,
    ];

    $electricityType = [
        ElectricityType::PREPAID,
        ElectricityType::POSTPAID
    ];

    $walletType = [
        WalletType::WALLET,
        WalletType::BONUS_WALLET
    ];
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'meter_number' => $faker->phoneNumber,
        'beneficiary_name' => $faker->name,
        'plan' => $faker->word,
        'type' =>$faker->randomElement($electricityType),
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'amount' => $faker->numberBetween(10000, 99999),
        'phone'=> $faker->phoneNumber,
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement($transactionStatus),
        'method'=> $faker->randomElement($walletType),
        'user_id' => $faker->numberBetween(1,5),
    ];
});
