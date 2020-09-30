<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Tests\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'password' => bcrypt('test'),
        'email' => $faker->safeEmail,
        'name' => $faker->userName
    ];
});
