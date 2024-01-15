<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\ProductPrice;
use Faker\Generator as Faker;

$factory->define(ProductPrice::class, function (Faker $faker) {

    return [
        'product_id' => $faker->randomDigitNotNull,
        'type_id' => $faker->randomDigitNotNull,
        'unit_id' => $faker->randomDigitNotNull,
        'city_id' => $faker->randomDigitNotNull,
        'unit_price' => $faker->word,
        'sale_price' => $faker->word,
        'is_active' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
