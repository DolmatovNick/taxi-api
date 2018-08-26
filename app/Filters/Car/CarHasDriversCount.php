<?php


namespace App\Filters\Car;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class CarHasDriversCount implements ICriteria {

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