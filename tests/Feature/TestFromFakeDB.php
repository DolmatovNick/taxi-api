<?php

namespace Tests\Feature;

use App\Status;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TestFromFakeDB extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();

        $this->createStatusesOneTime();
    }

    private function createStatusesOneTime()
    {
        DB::table('statuses')->insert([
            ['name' => 'Заказ поступил',        'id' => Status::OPERATOR_ACCEPTED_ORDER],
            ['name' => 'Водитель выехал',       'id' => Status::DRIVER_RIDING_TO_THE_CLIENT],
            ['name' => 'Водитель на месте',     'id' => Status::DRIVER_WAIT_THE_CLIENT],
            ['name' => 'Клиент в машине',       'id' => Status::CLIENT_IN_THE_CAR],
            ['name' => 'Клиент доставлен',      'id' => Status::CLIENT_DELIVERED],
            ['name' => 'Заказ выполнен',        'id' => Status::ORDER_COMPLETE],
            ['name' => 'Заказ не выполнен и закрыт',        'id' => Status::ORDER_ABORTED_AND_CLOSED],
        ]);
    }

}