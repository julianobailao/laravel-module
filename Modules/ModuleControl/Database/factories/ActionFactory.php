<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\Action;

$factory->define(Action::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->name,
    ];
});
