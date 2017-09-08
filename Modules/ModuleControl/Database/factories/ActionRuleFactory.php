<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\ActionRule;

$factory->define(ActionRule::class, function (Faker $faker) {
    return [
        'module_name' => $faker->name,
        'route_uri' => $faker->name,
        'route_method' => $faker->name,
    ];
});
