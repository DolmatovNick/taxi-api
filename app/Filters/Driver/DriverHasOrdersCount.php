<?php


namespace App\Filters\Driver;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class DriverHasOrdersCount implements ICriteria {

    /**
     * @var int|null
     */
    private $min;
    /**
     * @var int|null
     */
    private $max;

    function __construct(?int $min, ?int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    public function meetCriteria(Builder $query)
    {
        $havingString = $this->getSqlHavingCondition($this->min, $this->max);

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