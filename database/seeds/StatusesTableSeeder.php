<?php

use App\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statuses')->insert([
            ['name' => 'Заказ поступил',        'id' => Status::OPERATOR_ACCEPTED_ORDER],
            ['name' => 'Водитель выехал',       'id' => Status::DRIVER_RIDING_TO_THE_CLIENT],
            ['name' => 'Водитель на месте',     'id' => Status::DRIVER_WAIT_THE_CLIENT],
            ['name' => 'Клиент в машине',       'id' => Status::CLIENT_IN_THE_CAR],
            ['name' => 'Клиент доставлен',      'id' => Status::CLIENT_DELIVERED],
            ['name' => 'Заказ выполнен',        'id' => Status::ORDER_COMPLETE],
        ]);
    }

}
