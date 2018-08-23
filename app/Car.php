<?php

namespace App;

use App\Filters\CarsFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Car extends Model
{
    public $timestamps = false;

    public function model()
    {
        return $this->belongsTo(CarModel::class, 'id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param Builder $query
     * @param CarsFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, CarsFilters $filters)
    {
        return $filters->apply($query);
    }


}
