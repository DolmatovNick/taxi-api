<?php

namespace App\Filters\Driver;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class DriverHas implements ICriteria {

    /**
     * @var array
     */
    private $values;

    function __construct(array $values)
    {
        $this->values = $values;
    }

    public function meetCriteria(Builder $query)
    {
        foreach ($this->values as $object) {
            $query->has($object);
        }
    }
}