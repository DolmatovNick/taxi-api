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
        (new DriverHas())->asScope($this->builder, $objects);
    }

    protected function notHave(array $objects)
    {
        (new DriverNotHas())->asScope($this->builder, $objects);
    }

    protected function orderStatus(int $status)
    {
        (new DriverHasOrdersWithStatuses())->asScope($this->builder, $status);
    }

    protected function haveOrdersCount(string $countJson)
    {
        list($min, $max) = $this->extractMinAndMaxFromJson($countJson);

        (new DriverHasOrdersCount())->asScope($this->builder, $min, $max);
    }

    protected function orderByOrders($sortDirection = 'ASC')
    {
        $sortDirection = $this->normalizeOrderBy($sortDirection);

        (new DriverOrderByOrdersCount())->asScope($this->builder, $sortDirection);
    }

}