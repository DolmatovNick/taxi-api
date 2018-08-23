<?php

namespace App\Filters;


class OrdersFilters extends Filters {

    protected $filters = ['dontHaveStatuses'];

    protected function dontHaveStatuses(array $statuses)
    {
        return $this->builder->notInStatuses($statuses);
    }

}