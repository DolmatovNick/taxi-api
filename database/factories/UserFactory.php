<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => 'admin',
        'email' => 'admin@mail.com',
        'password' => '$2y$10$uilLyDPt1yxfJb46zHJ40OeJ1pklwz1HKlRdki4Au17VZWvUmPMNq',
        'api_token' => '4f1cc459-a229-43b2-abae-cc2e46e56cbe',
        'remember_token' => str_random(10),
    ];
});
