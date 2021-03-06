<?php

namespace App\Http\Controllers;

use App\Car;
use App\Filters\CarsFilters;
use App\Http\Resources\CarResource;

class CarController extends ApiController
{

    public function index(CarsFilters $filter)
    {
        $cars = CarResource::collection(
            Car::filter($filter)->paginate(5)
        );

        return $this->getCollectionResponse($cars);
    }

}
