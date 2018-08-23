<?php

use Illuminate\Database\Seeder;

class OrderPointsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = App\Order::all();

        foreach($orders as $order) {
            factory('App\OrderPoint')->create(['order_id' => $order->id, 'point_type' => 'pickup']);

            if ( $this->orderHasAdditionalPoint() ) {
                factory('App\OrderPoint')->create(['order_id' => $order->id, 'point_type' => 'pickup']);
            }

            if ( $this->orderHasAdditionalPoint() ) {
                factory('App\OrderPoint')->create(['order_id' => $order->id, 'point_type' => 'stepout']);
            }

            factory('App\OrderPoint')->create(['order_id' => $order->id, 'point_type' => 'stepout']);
        }
    }

    private function orderHasAdditionalPoint() : bool
    {
        return rand(1, 100) <= 20 ? true : false;
    }
}
