<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters {

    protected $filters = [];

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * ThreadFilters constructor.
     * @param Request $request
     */
    function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->getFilters() as $filter => $value) {
            if ( method_exists($this, $filter) ) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return $this->request->only($this->filters);
    }

    /**
     * @param string $countJson
     * @return array
     */
    protected function extractMinAndMaxFromJson(string $countJson): array
    {
        $count = \json_decode($countJson, JSON_OBJECT_AS_ARRAY);
        $min = $count['min'] ?? null;
        $max = $count['max'] ?? null;
        return array($min, $max);
    }

    /**
     * @param $sortDirection
     * @return string
     */
    protected function normalizeOrderBy($sortDirection): string
    {
        $sortDirection = strtolower($sortDirection);
        if ( !in_array($sortDirection, ['asc', 'desc']) ) {
            $sortDirection = 'asc';
            return $sortDirection;
        }
        return $sortDirection;
    }

}