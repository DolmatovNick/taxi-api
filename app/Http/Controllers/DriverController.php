<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Filters\DriversFilters;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use Illuminate\Pagination\LengthAwarePaginator;

class DriverController extends Controller
{

    public function index(DriversFilters $filter)
    {
        $drivers = Driver::filter($filter)->select('drivers.*')->paginate(5);

        return $this->getResponse($drivers);
    }

    private function getResponse(LengthAwarePaginator $drivers)
    {
        $collection = DriverResource::collection($drivers);

        if ( $drivers->total() == 0 ) {
            return $collection->response()->setStatusCode(404);
        }

        return $collection;
    }
}
