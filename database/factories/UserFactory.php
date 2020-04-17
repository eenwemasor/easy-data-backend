<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'full_name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => $faker->phoneNumber,
        'wallet' => $faker->numberBetween(1000, 2000), // password
        'accessibility' => \App\Enums\AccountAccessibility::USER,
        'email_confirmed' => $faker->boolean,
        'phone_verified' => $faker->boolean,
        'unique_id' => $faker->unique()->word,
        'active' => $faker->boolean,
        'username' => $faker->unique()->name(),
        'bonus_wallet' => $faker->numberBetween(1000, 2000),
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    ];
});
