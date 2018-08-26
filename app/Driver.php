<?php

namespace App;

use App\Filters\Driver\DriverHas;
use App\Filters\Driver\DriverHasOrdersCount;
use App\Filters\DriversFilters;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class Driver
 * @package App
 */
class Driver extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @param Builder $query
     * @param DriversFilters $filters
     * @return Builder
     */
    public function scopeFilter(Builder $query, DriversFilters $filters)
    {
        return $filters->apply($query);
    }

}
