<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Filters\DriversFilters;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends Controller
{

    protected function getResponse(ResourceCollection $collection)
    {
        if ( $collection->resource->total() == 0 ) {
            return $collection->response()->setStatusCode(404);
        }

        return $collection;
    }
}
