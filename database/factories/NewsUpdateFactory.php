<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\NewsUpdate;
use Faker\Generator as Faker;

$factory->define(NewsUpdate::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'body' => $faker->sentence(40),
    ];
});
