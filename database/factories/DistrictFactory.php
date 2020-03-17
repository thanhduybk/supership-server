<?php

/** @var Factory $factory */

use App\District;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(District::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
