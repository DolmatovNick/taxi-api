<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;

class ApiTest extends TestFromFakeDB
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
    }

    public function getApiPoints()
    {
        return [
            ['/api/v1/drivers', 'App\Driver', 10, 'fio'],
            ['/api/v1/cars', 'App\Car', 10, 'name'],
            ['/api/v1/orders', 'App\Order', 10, 'id'],
        ];
    }

    /**
     * @test
     * @dataProvider getApiPoints
    */
    public function it_show_api_points_are_working($point, $class, $count, $checkField)
    {
        $items = factory($class, $count)->create();

        $this->get($point)
            ->assertStatus(200)
            ->assertSee('"total":'.$count)
            ->assertSee($items[0]->$checkField);
    }

    /**
     * @dataProvider getApiPoints
     * @test
    */
    public function it_show_not_implemented_status_for_not_implemented_methods($point)
    {
        $this->withExceptionHandling();

        $methods = ['POST', 'PUT', 'PATCH', 'DELETE'];

        foreach ($methods as $method) {
            $response = $this->json($method, $point, []);

            $response->assertStatus(501);
        }
    }

    /**
     * @test
    */
    public function is_show_not_implemented_for_not_existed_point()
    {
        $this->withExceptionHandling();

        $methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];

        foreach ($methods as $method) {
            $response = $this->json($method, '/api/v1/not-existed-point', []);

            $response->assertStatus(501);
        }
    }

    /**
     * @dataProvider getApiPoints
     * @test
    */
    public function you_see_not_found_if_it_has_no_data($point)
    {
        $this->get($point)
            ->assertStatus(404)
            ->assertSee('Not found');
    }
    

}
