<?php

use App\Status;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $drivers = \App\Driver::take(10)->get();

        $operators = \App\Operator::where('position_id', 2)->take(10)->get();

        $cars = \App\Car::take(30)->get();

        $orders = [];
        foreach (range(1, 100) as $index) {

            $order = factory('App\Order')->create([
                'driver_id' => $this->getRandomIdFrom($drivers->toArray()),
                'car_id' => $this->getRandomIdFrom($cars->toArray()),
                'operator_id' => $this->getRandomIdFrom($operators->toArray()),
                'status_id' => Status::OPERATOR_ACCEPTED_ORDER,
            ]);

            $orders[] = $order;
        }

    }

    private function getRandomIdFrom($array)
    {
        return $array[
            \array_rand($array)
        ]['id'];
    }

}
