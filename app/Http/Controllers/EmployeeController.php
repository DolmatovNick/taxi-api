<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeResource;
use Illuminate\Support\Facades\DB;

class EmployeeController extends ApiController
{

    public function index()
    {
        $employees = EmployeeResource::collection(
            DB::query()
            ->fromSub(
                DB::table('operators')->union( DB::table('drivers')
                ), "EmployeeQuery")
            ->orderBy('fio')
            ->paginate(5)
        );

        return $this->getResponse($employees);
    }
}
