<?php
/**
 * Created by PhpStorm.
 * User: Pupsik
 * Date: 26.08.2018
 * Time: 7:38
 */

namespace App\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface ICriteria {

    public function meetCriteria(Builder $builder);

}