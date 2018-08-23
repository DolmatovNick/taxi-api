<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Http\Resources\EmployeeResource;

class EmployeeController extends ApiController
{

    public function index()
    {
        $employees = EmployeeResource::collection(
            Employee::orderBy('fio')->paginate(5)
        );

        return $this->getResponse($employees);
    }
}
