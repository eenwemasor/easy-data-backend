<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\AirtimeTransaction;
use Faker\Generator as Faker;

$factory->define(/**
 * @param Faker $faker
 * @return array
 */ AirtimeTransaction::class, function (Faker $faker) {
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'phone' => $faker->phoneNumber,
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'price' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement(["Delivered", "Sent", "Failed"]),
        'method'=> $faker->randomElement(["WALLET", "BONUS_WALLET"]),
        'user_id'=> $faker->numberBetween(1,5),
    ];
});
