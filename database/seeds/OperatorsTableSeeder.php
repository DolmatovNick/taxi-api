<?php

use Illuminate\Database\Seeder;

class OperatorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Operator', 1)->create(['position_id' => 3]);
        factory('App\Operator', 10)->create(['position_id' => 2]);
    }
}
