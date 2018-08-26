<?php

namespace Tests\Feature;

use App\Order;
use App\Status;

class CarsFilterTest extends TestFromFakeDB
{
    const apiPoint = '/api/v1/cars?';

    public function getSelectFilters()
    {
        return [
            // &haveDriversCount={"min":1,"max":5}
            [
                static::apiPoint,
                function() {
                    $cars = factory('App\Car', 3)->create();

                    factory('App\Order', 1)->create(['car_id' => $cars[0]->id]);
                    factory('App\Order', 5)->create(['car_id' => $cars[1]->id]);
                    factory('App\Order', 8)->create(['car_id' => $cars[2]->id]);

                },
                function() {
                    return '&haveDriversCount={"min":1,"max":5}';
                },
                2
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