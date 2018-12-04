<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Brand::class, function (Faker $faker) {
    return [
        'name' => rtrim($faker->sentence(1), '.'),
    ];
});
