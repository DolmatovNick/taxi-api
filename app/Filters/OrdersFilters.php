<?php

namespace App\Filters;

use App\Filters\Order\OrderNotInStatus;

class OrdersFilters extends Filters {

    protected $filters = ['notInStatus'];

    protected function notInStatus(int $status)
    {
        (new OrderNotInStatus($status))->meetCriteria($this->builder);
    }

}