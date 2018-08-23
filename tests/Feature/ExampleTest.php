<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{

    use DatabaseMigrations;

    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function main_page_work()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    
    /**
     * @test
    */
    public function guest_may_not_see_drivers_point()
    {
        $this->withExceptionHandling();

        $this->get('/api/v1/drivers')
            ->assertRedirect('/login');
    }
}
