<?php

use App\Status;
use Carbon\Carbon;
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

        $time = $this->orderStartDateTime();

        $driversTime = [];
        $drivers->each(function($driver) use($time, &$driversTime) {
            $driversTime[$driver->id] = $time;
        });

        $orders = [];
        foreach (range(1, 100) as $index) {

            $driver_id = $this->getRandomIdFrom($drivers->toArray());

            $order = factory('App\Order')->create([
                'driver_id' => $driver_id,
                'car_id' => $this->getRandomIdFrom($cars->toArray()),
                'operator_id' => $this->getRandomIdFrom($operators->toArray()),
                'status_id' => Status::OPERATOR_ACCEPTED_ORDER,
                'created_at' => $driversTime[$driver_id],
            ]);

            $driversTime[$driver_id]->addMinutes(60);
            $orders[] = $order;
        }

    }

    private function getRandomIdFrom($array)
    {
        return $array[
            \array_rand($array)
        ]['id'];
    }

    /**
     * @return Carbon
     */
    private function orderStartDateTime() : Carbon
    {
        $time = new Carbon();
        $time = $time->addSecond(rand(-200000, 100));
        return $time;
    }

}
