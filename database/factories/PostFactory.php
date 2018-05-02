<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'category_id' => 1, 
        'title' => $faker->name , 
        'slug' => $faker->uuid ,  
        'meta_description'  => $faker->sentence , 
        'files'  => $faker->image($dir = '/tmp', $width = 640, $height = 480) , 
        'related_post' => '[1,2,3]'
    ];
});
