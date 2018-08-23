<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;

    public const OPERATOR_ACCEPTED_ORDER = 1;
    public const DRIVER_RIDING_TO_THE_CLIENT = 2;
    public const DRIVER_WAIT_THE_CLIENT = 3;
    public const CLIENT_IN_THE_CAR = 4;
    public const CLIENT_DELIVERED = 5;
    public const ORDER_COMPLETE = 6;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
