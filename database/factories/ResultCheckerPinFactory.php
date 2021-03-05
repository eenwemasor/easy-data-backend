<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\ResultCheckerPin;
use Faker\Generator as Faker;

$factory->define(ResultCheckerPin::class, function (Faker $faker) {
    return [
        'pin' => $faker->numberBetween(1000000000000000, 9999999999999999),
        'serial_number' =>$faker->numberBetween(1000000000000000, 9999999999999999),
        'result_check_transaction_id' => $faker->randomElement([1,2,3,4]),
    ];
});
