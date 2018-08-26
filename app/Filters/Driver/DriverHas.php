<?php

namespace App\Filters\Driver;

use Illuminate\Database\Eloquent\Builder;

class DriverHas {

    public function asScope(Builder $query, array $objects)
    {
        foreach ($objects as $object) {
            $query->has($object);
        }
    }

}