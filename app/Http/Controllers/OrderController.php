<?php

namespace App\Http\Controllers;

use App\Filters\OrdersFilters;
use App\Http\Resources\OrderResource;
use App\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{

    public function index(OrdersFilters $filter)
    {
        $orders = Order::filter($filter)
            ->with(['operator', 'points'])
            ->paginate(5);

        return $this->getResponse($orders);
    }

    private function getResponse(LengthAwarePaginator $orders)
    {
        $collection = OrderResource::collection($orders);

        if ( $orders->total() == 0 ) {
            return $collection->response()->setStatusCode(404);
        }

        return $collection;
    }
}
