<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use MayIFit\Extension\Shop\Models\Customer;
use Faker\Generator as Faker;

$factory->define(Customer::class, function (Faker $faker) {
    $companyBilling = $faker->numberBetween(1, 100) > 70 ? TRUE : FALSE;
    $differentBillingAddress = $faker->numberBetween(1, 100) > 80 ? TRUE : FALSE;

    $retData = [
        'title' => $faker->title($gender = null),
        'first_name' => $faker->firstName($gender = null),
        'last_name' => $faker->lastName,
        'country' => $faker->country,
        'city' => $faker->city,
        'zip_code' => $faker->postcode,
        'address' => $faker->streetName,
        'house_nr' => $faker->buildingNumber,
        'floor' => $faker->numberBetween(1, 20),
        'door' => $faker->numberBetween(1, 20),
        'phone_number' => $faker->e164PhoneNumber,
        'email' => $faker->safeEmail,
    ];

    if ($differentBillingAddress) {
        $retData['different_billing_address'] = true;
        $retData['billing_first_name'] = $faker->firstName($gender = null);
        $retData['billing_last_name'] = $faker->lastName;
        $retData['billing_country'] = $faker->country;
        $retData['billing_city'] = $faker->city;
        $retData['billing_zip_code'] = $faker->postcode;
        $retData['billing_address'] = $faker->streetName;
        $retData['billing_house_nr'] = $faker->buildingNumber;
        $retData['billing_floor'] = $faker->numberBetween(1, 20);
        $retData['billing_door'] = $faker->numberBetween(1, 20);
    }

    if ($companyBilling) {
        $retData['company_billing'] = true;
        $retData['billing_vat_number'] = 'HU-43643643-235';
        $retData['billing_company_name'] = $faker->company;
    }

    return $retData;
});
