<?php

use Faker\Generator as Faker;

$factory->define(App\Todo::class, function (Faker $faker) {
    return [
    	'title' => $faker->name,
    	'description' => $faker->sentence(10,true)
    ];
});
