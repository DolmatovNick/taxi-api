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
        $this->clearDB();

        $this->createUsers();
        $this->createPositions();
        $this->createStatuses();

        $orders = $this->createOrders(50);

        $this->addPointsToOrders($orders);

        foreach ($orders as $order) {

            $time = $this->orderStartDateTime();

            // 1 Operator accepted the order
            $this->changeOrderStatuseTo($order, Status::OPERATOR_ACCEPTED_ORDER, $time);
            $this->setOrderStatus($order, Status::OPERATOR_ACCEPTED_ORDER);
            if ($this->orderStoppedOnThisStep()) continue;

            // 2 Operator call to driver, and driver has moved to the client
            $time = $this->plusTimeForCallingToDriver($time);
            $this->changeOrderStatuseTo($order, Status::DRIVER_RIDING_TO_THE_CLIENT, $time);
            $this->setOrderStatus($order, Status::DRIVER_RIDING_TO_THE_CLIENT);
            if ($this->orderStoppedOnThisStep()) continue;

            // 3 Driver wait the client
            $time = $this->plusTimeForMoving($time);
            $this->changeOrderStatuseTo($order, Status::DRIVER_WAIT_THE_CLIENT, $time);
            $this->setOrderStatus($order, Status::DRIVER_WAIT_THE_CLIENT);
            if ($this->orderStoppedOnThisStep()) continue;

            // 4 The client is in the car
            $time = $this->plusTimeForWaitingClient($time);
            $this->changeOrderStatuseTo($order, Status::CLIENT_IN_THE_CAR, $time);
            $this->setOrderStatus($order, Status::CLIENT_IN_THE_CAR);
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
            $this->changeOrderStatuseTo($order, Status::CLIENT_DELIVERED, $time);
            $this->setOrderStatus($order, Status::CLIENT_DELIVERED);
            if ($this->orderStoppedOnThisStep()) continue;

            // 6 Order closed
            $time = $this->plusTimeForCloseOrder($time);
            $this->changeOrderStatuseTo($order, Status::ORDER_COMPLETE, $time);
            $this->setOrderStatus($order, Status::ORDER_COMPLETE);
        }
    }

    private function setOrderStatus(Order $order, int $status)
    {
        $order->status_id = $status;
        $order->save();
    }

    private function orderHasAdditionalPoint() : bool
    {
        return rand(1, 100) <= 20 ? true : false;
    }

    public function orderStoppedOnThisStep() : bool
    {
        return rand(1, 100) <= 10 ? true : false;
    }

    private function plusTimeForCloseOrder($timestamp) {
        return $timestamp + rand(60, 180);
    }

    private function plusTimeForWaitingClient($timestamp) {
        return $timestamp + rand(60, 180);
    }

    private function plusTimeForCallingToDriver($timestamp) {
        return $timestamp + rand(60, 180);
    }

    private function plusTimeForMoving($timestamp) {
        return $timestamp + rand(130, 1800);
    }

    private function changeOrderStatuseTo($order, $status, int $timestamp)
    {
        $dt = new DateTime();
        $dt->setTimestamp($timestamp);

        DB::table('order_status')->insert([
            ['order_id' => $order->id, 'status_id' => $status, 'created_at' => $dt->format('Y-m-d H:i:s')]
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



    /**
     * @param $orders
     */
    protected function addPointsToOrders($orders)
    {
        foreach ($orders as $order) {
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

    /**
     * @return DateTime|int
     */
    protected function orderStartDateTime()
    {
        $time = new DateTime();
        $time = $time->getTimestamp() + rand(-100000, 100000);
        return $time;
    }

    private function getOrderStatus(){

        if ( rand(1, 100) < 21 ) {
            return rand(Status::OPERATOR_ACCEPTED_ORDER, Status::CLIENT_DELIVERED);
        }

        return Status::ORDER_COMPLETE;
    }

    private function createStatuses() {
        DB::table('statuses')->insert([
            ['name' => 'Заказ поступил',        'id' => Status::OPERATOR_ACCEPTED_ORDER],
            ['name' => 'Водитель выехал',       'id' => Status::DRIVER_RIDING_TO_THE_CLIENT],
            ['name' => 'Водитель на месте',     'id' => Status::DRIVER_WAIT_THE_CLIENT],
            ['name' => 'Клиент в машине',       'id' => Status::CLIENT_IN_THE_CAR],
            ['name' => 'Клиент доставлен',      'id' => Status::CLIENT_DELIVERED],
            ['name' => 'Заказ выполнен',        'id' => Status::ORDER_COMPLETE],
        ]);
    }

    protected function createPositions(): void
    {
        DB::table('positions')->insert([
            ['name' => 'driver', 'id' => 1],
            ['name' => 'call Operator', 'id' => 2],
            ['name' => 'director', 'id' => 3]
        ]);
        factory('App\Position', 10)->create();
    }

    protected function createUsers(): void
    {
        factory(App\User::class)->create();
    }

    /**
     * @return array
     */
    protected function createOrders(int $count): array
    {
        factory('App\Driver', 5)->create(['position_id' => 1]);
        $drivers = factory('App\Driver', 10)->create(['position_id' => 1]);

        factory('App\Operator', 1)->create(['position_id' => 3]);
        $operators = factory('App\Operator', 10)->create(['position_id' => 2]);

        factory('App\Car', 10)->create();
        $cars = factory('App\Car', 30)->create();

        $orders = [];
        for ($i = 1; $i <= $count; $i++) {
            $order = factory('App\Order')->create([
                'driver_id' => $this->getRandomIdFrom($drivers->toArray()),
                'car_id' => $this->getRandomIdFrom($cars->toArray()),
                'operator_id' => $this->getRandomIdFrom($operators->toArray()),
                'status_id' => Status::OPERATOR_ACCEPTED_ORDER,
            ]);

            $orders[] = $order;
        }
        return $orders;
    }

    private function getRandomIdFrom($array)
    {
        return $array[
            \array_rand($array)
        ]['id'];
    }
}
