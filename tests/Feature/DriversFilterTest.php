<?php

namespace Tests\Feature;

use App\Order;
use App\Status;

class DriversFilterTest extends TestFromFakeDB
{
    const apiPoint = '/api/v1/drivers?';

    public function getSelectFilters()
    {
        return [
            // &orderStatus=6
            [
                static::apiPoint,
                function() {
                    $orders = factory('App\Order', 10)->create();
                    $this->orderSetStatus($orders[0], Status::ORDER_COMPLETE);
                    $this->orderSetStatus($orders[1], Status::ORDER_COMPLETE);
                },
                function() {
                    return '&orderStatus='.Status::ORDER_COMPLETE;
                },
                2
            ],
            // &notHave[]=orders
            [
                static::apiPoint,
                function() {
                    factory('App\Order', 3)->create();
                    factory('App\Driver', 5)->create();
                },
                function() {
                    return $this->setArrayFilter('notHave',['orders']);
                },
                5
            ],
            // &have[]=orders
            [

                static::apiPoint,
                function() {
                    factory('App\Order', 3)->create();
                    factory('App\Driver', 5)->create();
                },
                function() {
                    return $this->setArrayFilter('have',['orders']);
                },
                3
            ],
            // &haveOrdersCount={"min":10,"max":15}
            [
                static::apiPoint,
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
            // '&haveOrdersCount={"min":10}
            [
                static::apiPoint,
                function() {
                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 1)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 10)->create(['driver_id' => $driver->id]);

                    $driver = factory('App\Driver')->create();
                    factory('App\Order', 15)->create(['driver_id' => $driver->id]);
                },
                function() {
                    return '&haveOrdersCount={"min":11}';
                },
                1
            ],
            // &haveOrdersCount={"max":10}
            [
                static::apiPoint,
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
            // &orderByOrders=ASC
            [
                static::apiPoint,
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
            // &orderByOrders=DESC
            [
                static::apiPoint,
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

    protected $filters = [
        '&orderStatus='.Status::OPERATOR_ACCEPTED_ORDER,
        '&have[]=orders',
        '&haveOrdersCount={"min":6,"max":6}',
        '&orderByOrders=DESC'
    ];

    public function getAllFiltersTogether()
    {
        return [
            // All-1
            [
                static::apiPoint,
                function() {
                    $drivers = factory('App\Driver', 6)->create();

                    $drivers->each(
                        function($driver) {
                            factory('App\Order', 5)->create(['driver_id' => $driver->id]);
                        }
                    );

                    factory('App\Order', 1)->create(['driver_id' => $drivers[5]->id]);

                    return $drivers[5];
                },
                function() {
                    return implode('', $this->filters);
                },
                1
            ],
        ];
    }

    public function getMergedFilters()
    {
        return array_merge(
            $this->getSortFilters(),
            $this->getAllFiltersTogether()
        );
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
     * @dataProvider getMergedFilters
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