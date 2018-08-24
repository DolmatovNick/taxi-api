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

$factory->define(App\Position::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\CarModel::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Driver::class, function (Faker $faker) {
    return [
        'fio' => $faker->name('male'),
        'position_id' => function() {
            return factory('App\Position')->create()->id;
        },
    ];
});

$factory->define(App\Operator::class, function (Faker $faker) {
    return [
        'fio' => $faker->name('female'),
        'position_id' => function() {
            return factory('App\Position')->create()->id;
        },
    ];
});

$factory->define(App\Status::class, function (Faker $faker) {
    return [
        'name' => $faker->word
    ];
});

$factory->define(App\Car::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'car_model_id' => function() {
            return factory('App\CarModel')->create()->id;
        },
        'number' => $faker->unique()->bothify('RUS ?###?? ##')
    ];
});

$factory->define(App\Order::class, function (Faker $faker) {

    $status_id = App\Status::OPERATOR_ACCEPTED_ORDER;

    return [
        'driver_id' => function() {
            return factory('App\Driver')->create()->id;
        },
        'operator_id' => function() {
            return factory('App\Operator')->create()->id;
        },
        'car_id' => function() {
            return factory('App\Car')->create()->id;
        },
        'status_id' => $status_id
    ];
});

$factory->define(App\OrderPoint::class, function (Faker $faker) {
    return [
        'order_id' => function() {
            return factory('App\Order')->create()->id;
        },
        'address' => $faker->address,
        'lat' => $faker->latitude(75, 76),
        'lng' => $faker->longitude(86, 90),
        'point_type' => function() {
            return rand(0, 1) == 1 ? 'pickup' : 'stepout';
        },

    ];
});

// TODO: Add statuses from DB::table('statuses)->insert(['id' => 1, 'name' => ])
