<?php

namespace App;

use App\Filters\OrdersFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function operator()
    {
        return $this->belongsTo(Operator::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function points()
    {
        return $this->hasMany(OrderPoint::class, 'order_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function statuses()
    {
        return $this->belongsToMany(Status::class);
    }

    /**
     * @param Builder $query
     * @param OrdersFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, OrdersFilters$filters)
    {
        return $filters->apply($query);
    }

    public function scopeInStatuses(Builder $query, array $statuses)
    {
        return $query->whereIn('status_id', $statuses);
    }

    public function scopeNotInStatuses(Builder $query, array $statuses)
    {
        return $query->whereNotIn('status_id', $statuses);
    }

}
