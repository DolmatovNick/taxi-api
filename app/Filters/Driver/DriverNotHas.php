<?php


namespace App\Filters\Driver;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class DriverNotHas implements ICriteria {

    /**
     * @var array
     */
    private $objects;

    function __construct(array $objects)
    {
        $this->objects = $objects;
    }

    public function meetCriteria(Builder $query)
    {
        foreach ($this->objects as $object) {
            return $query->doesntHave($object);
        }
    }

}