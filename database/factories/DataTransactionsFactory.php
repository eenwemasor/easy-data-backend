<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DataTransaction;
use Faker\Generator as Faker;

$factory->define(DataTransaction::class, function (Faker $faker) {
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'network' => $faker->randomElement(["GLO", "MTN", "ETISALAT", "AIRTEL"]),
        'data' => $faker->randomElement(["1.3GB","1.3GB","4GB","2.3GB","1.5GB","10GB"]),
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'price' => $faker->numberBetween(10000, 99999),
        'beneficiary' => $faker->phoneNumber,
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement(["Delivered", "Sent", "Failed"]),
        'method'=> $faker->randomElement(["WALLET", "BONUS_WALLET"]),
        'user_id' => $faker->numberBetween(1,5),
    ];
});
