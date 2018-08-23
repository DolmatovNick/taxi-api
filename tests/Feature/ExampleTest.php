<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
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
}
