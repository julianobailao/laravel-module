<?php

use Faker\Generator as Faker;
use Modules\ModuleControl\Entities\Action;
use Modules\ModuleControl\Entities\ActionGroup;

$factory->define(Action::class, function (Faker $faker) {
    static $action_group_id;

    return [
        'title' => $faker->name,
        'description' => $faker->name,
        'action_group_id' => function () use ($action_group_id) {
            return $action_group_id ?: factory(ActionGroup::class)->create()->id;
        },
    ];
});
