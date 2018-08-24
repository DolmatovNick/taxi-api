<?php

namespace Tests\Feature;

use App\Employee;
use MichaelAChrisco\ReadOnly\ReadOnlyException;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    /**
     * @test
     */
    public function employee_is_read_only_model()
    {
        $this->expectException(ReadOnlyException::class);
        Employee::create(['fio' => 'Test FIO']);
    }
}