<?php

namespace App\Filters;

use App\Filters\Order\OrderNotInStatuses;

class OrdersFilters extends Filters {

    protected $filters = ['notInStatus'];

    protected function notInStatus(int $statuses)
    {
        (new OrderNotInStatuses())->asScope($this->builder, $statuses);
    }

}