<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{

    public function index()
    {
        $employees = DB::table('operators')->union( DB::table('drivers') );

        $query = DB::query()
            ->fromSub($employees, "EmployeeQuery")
            ->orderBy('fio');

        return $this->getResponse($query->paginate(5));
    }

    private function getResponse(LengthAwarePaginator $drivers)
    {
        $collection = EmployeeResource::collection($drivers);

        if ( $drivers->total() == 0 ) {
            return $collection->response()->setStatusCode(404);
        }

        return $collection;
    }
}
