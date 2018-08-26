<?php


namespace App\Filters;


trait HttpTransform {

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