<?php


namespace App\Filters\Driver;

use Illuminate\Database\Eloquent\Builder;

class DriverOrderByOrdersCount {

    public function asScope(Builder $query, string $sortDirection)
    {
        $query
            ->leftJoin('orders', 'orders.driver_id', '=', 'drivers.id')
            ->groupBy('drivers.id', 'drivers.fio', 'drivers.position_id')
            ->orderByRaw("count(orders.driver_id) {$sortDirection}");
    }

}