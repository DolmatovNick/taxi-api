<?php

namespace App\Filters;


class DriversFilters extends Filters {

    protected $filters = [
        'have',
        'nothave',
        'haveOrdersCount', 'haveStatuses', 'orderByOrdersCount'
    ];

    protected function have(array $objects)
    {
        foreach ($objects as $object) {
            $this->builder->has($object);
        }
    }

    protected function notHave(array $objects)
    {
        foreach ($objects as $object) {
            $this->builder->doesntHave($object);
        }
    }

    protected function haveStatuses(array $statuses)
    {
        return $this->builder->whereHas('orders', function($orders) use($statuses){
            $orders->inStatuses($statuses);
        });
    }

    protected function haveOrdersCount($countJson)
    {
        $getSqlHavingCondition = function ($countJson)
        {
            $count = \json_decode($countJson);

            $sql = [];
            if ( isset($count->min) ) {
                $min = (int) $count->min;
                $sql[] = "COUNT(orders.driver_id) >= {$min}";
            }
            if (isset($count->max)) {
                $max = (int) $count->max;
                $sql[] = "COUNT(orders.driver_id) <= {$max}";
            }

            return implode(' AND ', $sql);

        };

        $havingString = $getSqlHavingCondition($countJson);

        return $this->builder->whereIn('id', function($query) use ($havingString) {

            if ($havingString != '') {
                $query->select('orders.driver_id')
                    ->from('orders')
                    ->groupBy('orders.driver_id')
                    ->havingRaw($havingString);
            }

        });
    }

    protected function orderByOrdersCount($sortDirection = 'ASC')
    {
        if ( !in_array(strtolower($sortDirection), ['asc', 'desc']) ) {
            $sortDirection = 'ASC';
        }

        $this->builder
            ->leftJoin('orders', 'orders.driver_id', '=', 'drivers.id')
            ->groupBy('drivers.id', 'drivers.fio', 'drivers.position_id')
            ->orderByRaw("count(orders.driver_id) {$sortDirection}");
    }

}