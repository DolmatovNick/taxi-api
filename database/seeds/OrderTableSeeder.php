<?php

use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Driver', 5)->create(['position_id' => 1]);
        $drivers = \App\Driver::pluck('id'); factory('App\Driver', 10)->create(['position_id' => 1]);

        factory('App\Operator', 1)->create(['position_id' => 3]);
        $operators = factory('App\Operator', 10)->create(['position_id' => 2]);

        factory('App\Car', 10)->create();
        $cars = factory('App\Car', 30)->create();

        $orders = [];
        for ($i = 1; $i <= $count; $i++) {
            $order = factory('App\Order')->create([
                'driver_id' => $this->getRandomIdFrom($drivers->toArray()),
                'car_id' => $this->getRandomIdFrom($cars->toArray()),
                'operator_id' => $this->getRandomIdFrom($operators->toArray())
            ]);

            $orders[] = $order;
        }
        return $orders;
    }
}
