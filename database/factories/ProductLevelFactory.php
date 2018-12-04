<?php

use Faker\Generator as Faker;

$factory->define(App\Models\ProductLevel::class, function (Faker $faker) {
    return [
        'name' => rtrim($faker->sentence(1), '.'),
    ];
});
