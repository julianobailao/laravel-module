<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\ActionGroup;

$factory->define(ActionGroup::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->name,
    ];
});
