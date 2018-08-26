<?php


namespace App\Filters\Driver;

use App\Filters\Contracts\ICriteria;
use Illuminate\Database\Eloquent\Builder;

class DriverHasOrdersWithStatus implements ICriteria {

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
        $query->whereHas('orders', function($orders){
            $orders->inStatus($this->status);
        });
    }

}