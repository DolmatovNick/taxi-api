<?php


namespace App\Filters\Driver;

use Illuminate\Database\Eloquent\Builder;

class DriverNotHas {

    public function asScope(Builder $query, array $objects)
    {
        foreach ($objects as $object) {
            return $query->doesntHave($object);
        }
    }

}