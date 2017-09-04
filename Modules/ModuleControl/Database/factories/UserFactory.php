<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\User;
use Modules\ModuleControl\Entities\UserGroup;

$factory->define(User::class, function (Faker $faker) {
    static $password, $user_group_id;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'user_group_id' => function () use ($user_group_id) {
            return $user_group_id ?: factory(UserGroup::class)->create()->id;
        },
    ];
});
