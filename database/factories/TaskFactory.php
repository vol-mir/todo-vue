<?php

use App\Models\Task;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(),
        'done' => false,
        'user_id' => User::select('id')->where('name', 'admin')->first()
    ];
});
