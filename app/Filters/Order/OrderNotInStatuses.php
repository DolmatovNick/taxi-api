<?php

namespace App\Filters\Order;

use Illuminate\Database\Eloquent\Builder;

class OrderNotInStatuses {

    public function asScope(Builder $query, int $statuses)
    {
        return $query->notInStatus($statuses);
    }

}