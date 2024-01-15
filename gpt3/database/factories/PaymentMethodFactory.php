<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\PaymentMethod;
use Faker\Generator as Faker;

$factory->define(PaymentMethod::class, function (Faker $faker) {

    return [
        'type' => $faker->word,
        'name' => $faker->word,
        'description' => $faker->text,
        'code' => $faker->word,
        'provider' => $faker->word,
        'is_active' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
