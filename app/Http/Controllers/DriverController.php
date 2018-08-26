<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Filters\DriversFilters;
use App\Http\Resources\DriverCollection;
use App\Http\Resources\DriverResource;
use Illuminate\Support\Facades\DB;

class DriverController extends ApiController {

    public function index(DriversFilters $filter)
    {
        // DB::enableQueryLog();

        $driversCollection = DriverResource::collection(
            Driver::filter($filter)->select('drivers.*')->paginate(5)
        );

        // dd( DB::getQueryLog() );

        return $this->getCollectionResponse($driversCollection);
    }

}
