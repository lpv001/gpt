<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\District;
use Faker\Generator as Faker;

$factory->define(District::class, function (Faker $faker) {

    return [
        'iso_code' => $faker->word,
        'city_province_id' => $faker->randomDigitNotNull,
        'default_name' => $faker->word,
        'slug' => $faker->word,
        'lat' => $faker->randomDigitNotNull,
        'lng' => $faker->randomDigitNotNull,
        'order' => $faker->randomDigitNotNull,
        'is_active' => $faker->word
    ];
});
