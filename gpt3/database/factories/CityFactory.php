<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\City;
use Faker\Generator as Faker;

$factory->define(City::class, function (Faker $faker) {

    return [
        'iso_code' => $faker->word,
        'default_name' => $faker->word,
        'slug' => $faker->word,
        'lat' => $faker->randomDigitNotNull,
        'lng' => $faker->randomDigitNotNull,
        'is_city' => $faker->word,
        'order' => $faker->randomDigitNotNull,
        'is_active' => $faker->word,
        'country_id' => $faker->randomDigitNotNull
    ];
});
