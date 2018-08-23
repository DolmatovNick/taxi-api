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
        // $this->clearDB();

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
        ]);

    }

    private function clearDB() {

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::table('statuses')->truncate();
        DB::table('users')->truncate();
        DB::table('positions')->truncate();
        DB::table('car_models')->truncate();
        DB::table('cars')->truncate();
        DB::table('orders')->truncate();
        DB::table('order_points')->truncate();
        DB::table('order_status')->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
