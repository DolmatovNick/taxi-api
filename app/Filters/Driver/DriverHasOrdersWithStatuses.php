<?php


namespace App\Filters\Driver;

use Illuminate\Database\Eloquent\Builder;

class DriverHasOrdersWithStatuses {

    public function asScope(Builder $query, int $status)
    {
        $query->whereHas('orders', function($orders) use($status){
            $orders->inStatus($status);
        });
    }

}