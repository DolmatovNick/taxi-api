<?php

namespace App\Filters;

use App\Filters\Driver\DriverHas;
use App\Filters\Driver\DriverHasOrdersCount;
use App\Filters\Driver\DriverNotHas;
use App\Filters\Driver\DriverHasOrdersWithStatuses;
use App\Filters\Driver\DriverOrderByOrdersCount;

class DriversFilters extends Filters {

    protected $filters = [
        'have',
        'notHave',
        'orderStatus',
        'haveOrdersCount',
        'orderByOrders'
    ];

    protected function have(array $objects)
    {
        (new DriverHas($objects))->meetCriteria($this->builder);
    }

    protected function notHave(array $objects)
    {
        (new DriverNotHas($objects))->meetCriteria($this->builder);
    }

    protected function orderStatus(int $status)
    {
        (new DriverHasOrdersWithStatuses($status))->meetCriteria($this->builder);
    }

    protected function haveOrdersCount(string $countJson)
    {
        list($min, $max) = $this->extractMinAndMaxFromJson($countJson);

        (new DriverHasOrdersCount($min, $max))->meetCriteria($this->builder);
    }

    protected function orderByOrders($sortDirection = 'ASC')
    {
        $sortDirection = $this->normalizeOrderBy($sortDirection);

        (new DriverOrderByOrdersCount($sortDirection))->meetCriteria($this->builder);
    }

}