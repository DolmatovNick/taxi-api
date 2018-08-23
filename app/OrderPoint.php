<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderPoint extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
