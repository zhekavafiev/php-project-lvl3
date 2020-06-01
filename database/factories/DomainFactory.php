<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Domain;
use Faker\Generator as Faker;

$factory->define(Domain::class, function (Faker $faker) {
    return [
        'name' => $faker->url,
        'id' => $faker->randomDigitnot(0)
    ];
});
