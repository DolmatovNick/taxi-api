<?php

namespace App\Filters\Order;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class OrderNotInStatus implements ICriteria {

    /**
     * @var int
     */
    private $status;

    function __construct(int $status)
    {
        $this->status = $status;
    }

    public function meetCriteria(Builder $query)
    {
        $query->notInStatus($this->status);
    }

}