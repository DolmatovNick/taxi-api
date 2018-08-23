<?php

namespace App\Http\Controllers;

use App\Filters\OrdersFilters;
use App\Http\Resources\OrderResource;
use App\Order;

class OrderController extends ApiController
{

    public function index(OrdersFilters $filter)
    {
        $orders = OrderResource::collection(
            Order::filter($filter)
            ->with(['operator', 'points'])
            ->paginate(5)
        );

        return $this->getResponse($orders);
    }

}
