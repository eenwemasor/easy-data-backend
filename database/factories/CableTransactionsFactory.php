<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CableTransaction;
use App\Enums\TransactionStatus;
use App\Enums\WalletType;
use Faker\Generator as Faker;

$factory->define(CableTransaction::class, function (Faker $faker) {
    $transactionStatus = [
        TransactionStatus::SENT,
        TransactionStatus::COMPLETED,
        TransactionStatus::PROCESSING,
        TransactionStatus::FAILED,
    ];
    $walletType = [
        WalletType::WALLET,
        WalletType::BONUS_WALLET
    ];
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'decoder' => $faker->randomElement(["DSTV","GOTV","STARTIMES"]),
        'decoder_number' => $faker->phoneNumber,
        'beneficiary_name' => $faker->name,
        'plan' => $faker->word,
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'amount' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement($transactionStatus),
        'method'=> $faker->randomElement($walletType),
        'user_id' => $faker->numberBetween(1,5),
    ];
});
