<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Position')->create(['name' => 'driver', 'id' => 1]);
        factory('App\Position')->create(['name' => 'call Operator', 'id' => 2]);
        factory('App\Position')->create(['name' => 'director', 'id' => 3]);
        factory('App\Position', 10)->create();
    }
}
