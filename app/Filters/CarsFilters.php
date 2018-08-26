<?php

namespace App\Filters;

use App\Filters\Car\CarHasDriversCount;

class CarsFilters extends Filters {

    protected $filters = ['haveDriversCount'];

    protected function haveDriversCount($countJson)
    {
        list($min, $max) = $this->extractMinAndMaxFromJson($countJson);

        (new CarHasDriversCount())->asScope($this->builder, $min, $max);
    }

}