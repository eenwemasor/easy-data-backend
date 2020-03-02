<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\ElectricityTransaction;
use Faker\Generator as Faker;

$factory->define(ElectricityTransaction::class, function (Faker $faker) {
    return [
        'reference' => $faker->numberBetween(10000000000000, 9999999999999999),
        'decoder' => $faker->randomElement(["DSTV","GOTV","STARTIMES"]),
        'decoder_number' => $faker->phoneNumber,
        'beneficiary_name' => $faker->name,
        'plan' => $faker->word,
        'initial_balance' => $faker->numberBetween(10000, 99999),
        'price' => $faker->numberBetween(10000, 99999),
        'new_balance' => $faker->numberBetween(10000, 99999),
        'status'=> $faker->randomElement(["Delivered", "Sent", "Failed"]),
        'method'=> $faker->randomElement(["WALLET", "BONUS_WALLET"]),
        'user_id' => $faker->numberBetween(1,5),
    ];
});
