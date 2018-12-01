<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text,
        'active' => $faker->boolean,
        'uuid' => $faker->uuid
    ];
});
