<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use MichaelAChrisco\ReadOnly\ReadOnlyTrait;

class Employee extends Model
{
    use ReadOnlyTrait;
}
