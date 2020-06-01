<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\DomainCheck;
use Faker\Generator as Faker;

$factory->define(DomainCheck::class, function (Faker $faker) {
    return [
        'id' => $faker->randomDigitNot(0),
        'domain_id' => ''
    ];
});
