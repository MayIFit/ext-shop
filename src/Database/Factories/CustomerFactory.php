<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'country' => $faker->country,
        'city' => $faker->city,
        'zip_code' => $faker->postcode,
        'address' => $faker->streetName,
        'house_nr' => $faker->buildingNumber,
        'phone_number' => $faker->phoneNumber,
        'email' => $faker->safeEmail
    ];
});
