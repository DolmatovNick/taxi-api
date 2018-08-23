<?php

namespace App\Http\Controllers;

use App\Car;
use App\Filters\CarsFilters;
use App\Http\Resources\CarResource;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CarController extends Controller
{

    public function index(CarsFilters $filter)
    {
        $cars = Car::filter($filter)->paginate(5);

        return $this->getResponse($cars);
    }

    private function getResponse(LengthAwarePaginator $orders)
    {
        $collection = CarResource::collection($orders);

        if ( $orders->total() == 0 ) {
            return $collection->response()->setStatusCode(404);
        }

        return $collection;
    }
}
