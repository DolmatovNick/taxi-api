<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    public $timestamps = false;

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
