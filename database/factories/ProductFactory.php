<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Product::class, function (Faker $faker) {
    $materials = ['wear', 'shoe', 'ball'];

    return [
        'brand_id'     => function () {
            return factory(App\Models\Brand::class)->create()->id;
        },
        'category_id'  => function () {
            return factory(App\Models\Category::class)->create()->id;
        },
        'dkt_id'       => $faker->randomNumber(7),
        'name'         => rtrim($faker->sentence),
        'description'  => $faker->realText(100),
        'source'       => $faker->url,
        'image'        => $faker->imageUrl,
        'price'        => $faker->numberBetween(10, 1000),
        'gtin'         => $faker->ean13,
        'color'        => $faker->word,
        'size'         => $faker->word,
        'material'     => $faker->randomElement($materials),
        'supermodel'   => str_random(9),
        'id_article'   => $faker->randomNumber(7),
        'review_count' => $faker->numberBetween(0, 500),
        'product_md5'  => $faker->md5,
        'is_prototype' => $faker->boolean(1),
    ];
});
