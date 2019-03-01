<?php

use Faker\Generator as Faker;
use App\Models\Product;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->postcode,
        'sku' => $faker->numberBetween(10, 50),
        'description' => $faker->sentence,
        'cost' => $faker->randomFloat(10, 33, 500),
        'price' => $faker->randomFloat(10, 10, 500),
        'imported_date' => $faker->date('Y-m-d'),
        'expired_at' => $faker->date('Y-m-d'),
        'category_id' => $faker->numberBetween(1, 10),
        'user_id' => $faker->numberBetween(1, 5)
    ];
});
