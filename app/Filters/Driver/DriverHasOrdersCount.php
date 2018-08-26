<?php


namespace App\Filters\Driver;

use Illuminate\Database\Eloquent\Builder;

class DriverHasOrdersCount {

    public function asScope(Builder $query, ?int $min, ?int $max)
    {
        $havingString = $this->getSqlHavingCondition($min, $max);

        $query->whereHas('orders', function ($orders) use ($havingString) {

            if ( $havingString != '' ) {
                $orders
                    ->groupBy('orders.driver_id')
                    ->havingRaw($havingString);
            }

        });
    }

    protected function getSqlHavingCondition(?int $min, ?int $max)
    {
        $sql = [];
        if ( isset($min) ) {
            $min = (int)$min;
            $sql[] = "COUNT(orders.driver_id) >= {$min}";
        }
        if ( isset($max) ) {
            $max = (int)$max;
            $sql[] = "COUNT(orders.driver_id) <= {$max}";
        }

        return implode(' AND ', $sql);

    }

}