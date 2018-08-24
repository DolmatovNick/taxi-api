<?php

namespace Tests\Feature;

use App\Order;
use App\Status;

class DriversFilterTest extends TestFromFakeDB
{
    public function getSelectFilters()
    {
        return [
            // statuses[]=6&statuses[]=5
            [
                '/api/v1/drivers?',
                function() {
                    $orders = factory('App\Order', 10)->create();
                    $this->orderSetStatus($orders[0], Status::ORDER_COMPLETE);
                    $this->orderSetStatus($orders[1], Status::CLIENT_DELIVERED);
                },
                function() {
                    return $this->setArrayFilter('haveStatuses',[Status::ORDER_COMPLETE, Status::CLIENT_DELIVERED]);
                },
                2
            ],
            // notHave[]=orders
            [
                '/api/v1/drivers?',
                function() {
                    factory('App\Order', 3)->create();
                    factory('App\Driver', 5)->create();
                },
                function() {
                    return $this->setArrayFilter('notHave',['orders']);
                },
                5
            ],
            // have[]=orders
            [

                '/api/v1/drivers?',
                function() {
                    factory('App\Order', 3)->create();
                    factory('App\Driver', 5)->create();
                },
                function() {
                    return $this->setArrayFilter('have',['orders']);
                },
                3
            ],
            // &haveOrdersCount={"min":13,"max":13}
            [
                '/api/v1/drivers?',
                function() {
                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 1)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 10)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 15)->create(['driver_id' => $driver->id]);
                },
                function() {
                    return '&haveOrdersCount={"min":10,"max":15}';
                },
                2
            ],
            // '&haveOrdersCount={"min":1}
            [
                '/api/v1/drivers?',
                function() {
                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 1)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 10)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 15)->create(['driver_id' => $driver->id]);
                },
                function() {
                    return '&haveOrdersCount={"min":10}';
                },
                2
            ],
            // &haveOrdersCount={"max":10}
            [
                '/api/v1/drivers?',
                function() {
                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 1)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 10)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 15)->create(['driver_id' => $driver->id]);
                },
                function() {
                    return '&haveOrdersCount={"max":10}';
                },
                2
            ]
        ];
    }

    public function getSortFilters()
    {
        return [
            // &orderByOrdersCount=ASC
            [
                '/api/v1/drivers?',
                function() {
                    $drivers = factory('App\Driver', 6)->create();

                    $i = 1;
                    foreach ($drivers as $driver) {
                        factory('App\Order', $i++)->create(['driver_id' => $driver->id]);
                    }

                    return $drivers[0];
                },
                function() {
                    return '&orderByOrders=ASC';
                },
                6
            ],
            // &orderByOrdersCount=DESC
            [
                '/api/v1/drivers?',
                function() {
                    $drivers = factory('App\Driver', 6)->create();

                    $i = 1;
                    foreach ($drivers as $driver) {
                        factory('App\Order', $i++)->create(['driver_id' => $driver->id]);
                    }

                    return $drivers[5];
                },
                function() {
                    return '&orderByOrders=DeSC';
                },
                6
            ],
        ];
    }

    /**
     * @dataProvider getSelectFilters
     * @test
     */
    public function it_filtered_by_filters($point, $getData, $getFilter, $totalCount)
    {
        $getData();

        $this->get($point.$getFilter())
            ->assertStatus(200)
            ->assertSee('"total":'.$totalCount);
    }

    /**
     * @dataProvider getSortFilters
     * @test
     */
    public function it_sorted_by_filters($point, $getData, $getFilter, $totalCount)
    {
        $driver = $getData();

        $this->get($point.$getFilter())
            ->assertStatus(200)
            ->assertSee($driver->fio)
            ->assertSee('"total":'.$totalCount);
    }

    private function orderSetStatus(Order $order, int $status)
    {
        $order->status_id = $status;
        $order->save();
    }

    public function setArrayFilter($filter, $statuses)
    {
        $filter = array_map(function($item) use($filter) {
            return '&'.$filter.'[]='.$item;
        }, $statuses);

        return implode('',$filter);
    }

}