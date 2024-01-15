<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Admin\Models\Payment;
use Faker\Generator as Faker;

$factory->define(Payment::class, function (Faker $faker) {

    return [
        'payment_method_id' => $faker->randomDigitNotNull,
        'order_id' => $faker->randomDigitNotNull,
        'amount' => $faker->word,
        'memo' => $faker->word,
        'created_at' => $faker->date('Y-m-d H:i:s'),
        'updated_at' => $faker->date('Y-m-d H:i:s')
    ];
});
