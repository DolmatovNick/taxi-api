<?php

namespace App\Filters;


class CarsFilters extends Filters {

    protected $filters = ['haveDriversCount'];

    protected function haveDriversCount($countJson)
    {
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

        return $this->builder->whereIn('id', function($query) use ($havingString) {

            if ($havingString != '') {
                $query
                    ->select('orders.car_id')
                    ->from('orders')
                    ->groupBy('orders.car_id')
                    ->havingRaw($havingString);
            }

        });
    }

}