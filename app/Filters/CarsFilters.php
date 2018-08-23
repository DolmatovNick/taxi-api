<?php

namespace App\Filters;


class CarsFilters extends Filters {

    protected $filters = ['haveDriversCount'];

    protected function haveDriversCount($countJson)
    {
        // &haveDriversCount={"min":1,"max":4}

        $extractHavingConditions = function($countJson) {
            $count = \json_decode($countJson);

            $sql = [];
            if ( isset($count->min) ) {
                $min = (int) $count->min;
                $sql[] = "COUNT(DISTINCT orders.driver_id) > {$min}";
            }
            if (isset($count->max)) {
                $max = (int) $count->max;
                $sql[] = "COUNT(DISTINCT orders.driver_id) <  {$max}";
            }

            return implode(' AND ', $sql);
        };

        $havingString = $extractHavingConditions($countJson);

        return $this->builder->whereHas('orders', function($orders) use ($havingString) {

            if ($havingString != '') {
                $orders
                    ->select('orders.car_id')
                    ->groupBy('orders.car_id')
                    ->havingRaw($havingString);
            }

        });
    }

}