<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\DeliveryProvider;
use Faker\Generator as Faker;

$factory->define(DeliveryProvider::class, function (Faker $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->text,
        'icon' => $faker->word,
        'cost' => $faker->word,
        'is_active' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
