<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Filters\DriversFilters;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;

class DriverController extends ApiController {

    public function index(DriversFilters $filter)
    {
        $driversCollection = DriverResource::collection(
            Driver::filter($filter)->select('drivers.*')->paginate(5)
        );

        return $this->getCollectionResponse($driversCollection);
    }

}
