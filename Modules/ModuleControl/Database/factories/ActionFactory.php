<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\Action;

$factory->define(Modules\ModuleControl\Entities\Action::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->name,
    ];
});
