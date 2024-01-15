<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\Delivery;
use Faker\Generator as Faker;

$factory->define(Delivery::class, function (Faker $faker) {

    return [
        'shop_id' => $faker->randomDigitNotNull,
        'provider_id' => $faker->randomDigitNotNull,
        'code' => $faker->word,
        'is_active' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
