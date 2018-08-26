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

            $time = $order->created_at->getTimestamp();

            // 1 Operator accepted the order
            $this->addOrderStatus($order, Status::OPERATOR_ACCEPTED_ORDER, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            if ($this->orderAborted()) {
                $this->setOrderAsAborted($time, $order);
                continue;
            }

            // 2 Operator call to driver, and driver has moved to the client
            $time = $this->plusTimeForCallingToDriver($time);
            $this->addOrderStatus($order, Status::DRIVER_RIDING_TO_THE_CLIENT, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            if ($this->orderAborted()) {
                $this->setOrderAsAborted($time, $order);
                continue;
            }

            // 3 Driver wait the client
            $time = $this->plusTimeForMoving($time);
            $this->addOrderStatus($order, Status::DRIVER_WAIT_THE_CLIENT, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            if ($this->orderAborted()) {
                $this->setOrderAsAborted($time, $order);
                continue;
            }

            // 4 The client is in the car
            $time = $this->plusTimeForWaitingClient($time);
            $this->addOrderStatus($order, Status::CLIENT_IN_THE_CAR, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            if ($this->orderAborted()) {
                $this->setOrderAsAborted($time, $order);
                continue;
            }

            /**
             * @var \App\OrderPoint $point
             */
            foreach ($order->points as $point) {
                $point->update([
                    'updated_at' => $time,
                ]);

                $time = $this->plusTimeForMoving($time);
                if ($this->orderStoppedOnThisStep()) continue;

                if ($this->orderAborted()) {
                    $this->setOrderAsAborted($time, $order);
                    continue;
                }
            }

            // 7 Client is delivered
            $time = $this->plusTimeForMoving($time);
            $this->addOrderStatus($order, Status::CLIENT_DELIVERED, $time);
            if ($this->orderStoppedOnThisStep()) continue;

            if ($this->orderAborted()) {
                $this->setOrderAsAborted($time, $order);
                continue;
            }

            // 6 Order closed
            $time = $this->plusTimeForCloseOrder($time);
            $this->addOrderStatus($order, Status::ORDER_COMPLETE, $time);
        }

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

    private function orderAborted() : bool
    {
        return rand(1, 100) <= 10 ? true : false;
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

    /**
     * @param $time
     * @param $order
     * @return mixed
     */
    protected function setOrderAsAborted($time, $order)
    {
        $time = $this->plusTimeForCallingToDriver($time);
        $this->addOrderStatus($order, Status::ORDER_ABORTED_AND_CLOSED, $time);
        return $time;
    }

}
