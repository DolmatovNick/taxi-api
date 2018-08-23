<?php

use App\Order;
use App\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orders = \App\Order::take(50)->get();

        foreach ($orders as $order) {

            $time = $this->orderStartDateTime();

            // 1 Operator accepted the order
            $this->addOrderStatus($order, Status::OPERATOR_ACCEPTED_ORDER, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            // 2 Operator call to driver, and driver has moved to the client
            $time = $this->plusTimeForCallingToDriver($time);
            $this->addOrderStatus($order, Status::DRIVER_RIDING_TO_THE_CLIENT, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            // 3 Driver wait the client
            $time = $this->plusTimeForMoving($time);
            $this->addOrderStatus($order, Status::DRIVER_WAIT_THE_CLIENT, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            // 4 The client is in the car
            $time = $this->plusTimeForWaitingClient($time);
            $this->addOrderStatus($order, Status::CLIENT_IN_THE_CAR, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            /**
             * @var \App\OrderPoint $point
             */
            foreach ($order->points as $point) {
                $point->update([
                    'updated_at' => $time,
                ]);

                $time = $this->plusTimeForMoving($time);
                if ($this->orderStoppedOnThisStep()) continue;
            }

            // 7 Client is delivered
            $time = $this->plusTimeForMoving($time);
            $this->addOrderStatus($order, Status::CLIENT_DELIVERED, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            // 6 Order closed
            $time = $this->plusTimeForCloseOrder($time);
            $this->addOrderStatus($order, Status::ORDER_COMPLETE, $time);
        }

    }

    /**
     * @return DateTime|int
     */
    private function orderStartDateTime()
    {
        $time = new DateTime();
        $time = $time->getTimestamp() + rand(-100000, 100000);
        return $time;
    }


    private function addOrderStatus(Order $order, $status, int $timestamp)
    {
        $dt = new DateTime();
        $dt->setTimestamp($timestamp);

        DB::table('order_status')->insert([
            ['order_id' => $order->id, 'status_id' => $status, 'created_at' => $dt->format('Y-m-d H:i:s')]
        ]);

        $order->status_id = $status;
        $order->save();
    }

    private function orderStoppedOnThisStep() : bool
    {
        return rand(1, 100) <= 10 ? true : false;
    }

    private function plusTimeForCallingToDriver($timestamp) {
        return $timestamp + rand(60, 180);
    }

    private function plusTimeForMoving($timestamp) {
        return $timestamp + rand(130, 1800);
    }

    private function plusTimeForWaitingClient($timestamp) {
        return $timestamp + rand(60, 180);
    }

    private function plusTimeForCloseOrder($timestamp) {
        return $timestamp + rand(60, 180);
    }

}