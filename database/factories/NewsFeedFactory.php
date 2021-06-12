<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\NewsFeed;
use Faker\Generator as Faker;

$factory->define(NewsFeed::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(4),
        'image_url' => $faker->imageUrl(),
        'body' => $faker->sentence(40),

    ];
});
