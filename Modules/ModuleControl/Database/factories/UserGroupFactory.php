<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\UserGroup;

$factory->define(UserGroup::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'description' => $faker->name,
    ];
});
