<?php

/** @var Factory $factory */

use App\Province;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Province::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
