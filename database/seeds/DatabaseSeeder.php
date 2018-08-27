<?php

use App\Order;
use App\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            StatusesTableSeeder::class,
            PositionsTableSeeder::class,

            DriversTableSeeder::class,
            OperatorsTableSeeder::class,

            UsersTableSeeder::class,

            CarsTableSeeder::class,

            OrdersTableSeeder::class,
            OrderPointsTableSeeder::class,
            OrderStatusesTableSeeder::class,

            PassportsTableSeeder::class
        ]);

    }

}
