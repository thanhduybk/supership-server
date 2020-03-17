<?php

/** @var Factory $factory */

use App\Model;
use App\Ward;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(Ward::class, function (Faker $faker) {
    return [
        'name' => $faker->name
    ];
});
