<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

use MayIFit\Extension\Shop\Models\Reseller;

$factory->define(Reseller::class, function (Faker $faker) {
    return [
        'phone_number' => $faker->numerify('########'),
        'email' => $faker->safeEmail,
        'vat_id' => $faker->numerify('############'),
        'company_name' => $faker->company,
        'contact_person' => $faker->jobTitle,
    ];
});
