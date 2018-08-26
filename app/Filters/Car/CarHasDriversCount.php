<?php


namespace App\Filters\Car;

use Illuminate\Database\Eloquent\Builder;

class CarHasDriversCount {

    public function asScope(Builder $query, ?int $min, ?int $max)
    {
        $havingString = $this->getSqlHavingCondition($min, $max);

        return $query->whereIn('id', function($query) use ($havingString) {

            if ($havingString != '') {
                $query
                    ->select('orders.car_id')
                    ->from('orders')
                    ->groupBy('orders.car_id')
                    ->havingRaw($havingString);
            }

        });
    }

    protected function getSqlHavingCondition(?int $min, ?int $max)
    {
        $sql = [];
        if ( isset($min) ) {
            $min = (int)$min;
            $sql[] = "COUNT(DISTINCT orders.driver_id) >= {$min}";
        }
        if ( isset($max) ) {
            $max = (int)$max;
            $sql[] = "COUNT(DISTINCT orders.driver_id) <= {$max}";
        }

        return implode(' AND ', $sql);

    }

}