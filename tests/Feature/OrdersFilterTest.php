<?php

namespace Tests\Feature;

use App\Order;
use App\Status;

class OrdersFilterTest extends TestFromFakeDB
{
    const apiPoint = '/api/v1/orders?';

    public function getSelectFilters()
    {
        return [
            // &notInStatus=6
            [
                static::apiPoint,
                function() {
                    factory('App\Order', 10)->create(['status_id' => Status::ORDER_COMPLETE]);
                    factory('App\Order', 3)->create(['status_id' => Status::CLIENT_DELIVERED]);
                    factory('App\Order', 6)->create(['status_id' => Status::CLIENT_IN_THE_CAR]);
                },
                function() {
                    return '&notInStatus='.Status::ORDER_COMPLETE;
                },
                9
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

}