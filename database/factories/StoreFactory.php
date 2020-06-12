<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Store::class, function (Faker $faker) {
    return [
        'seller_id' => 2,
        'name' => 'klekt',
        'description' => 'nil'
    ];
});
