<?php


namespace App\Filters\Driver;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class DriverOrderByOrdersCount implements ICriteria {

    /**
     * @var string
     */
    private $sortDirection;

    /**
     * DriverOrderByOrdersCount constructor.
     * @param string $sortDirection
     */
    function __construct(string $sortDirection)
    {
        $this->sortDirection = $sortDirection;
    }

    public function meetCriteria(Builder $query)
    {
        $query
            ->leftJoin('orders', 'orders.driver_id', '=', 'drivers.id')
            ->groupBy('drivers.id', 'drivers.fio', 'drivers.position_id')
            ->orderByRaw("count(orders.driver_id) {$this->sortDirection}");
    }

}