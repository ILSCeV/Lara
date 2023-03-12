<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_main_page_is_accessable()
    {
        $response = $this->followingRedirects()->get('/');
        $response->assertStatus(200);
    }

    public function test_week_page_is_accessable()
    {
        $response = $this->followingRedirects()->get('/calendar/week');
        $response->assertStatus(200);
    }

    public function test_month_page_is_accessable()
    {
        $response = $this->followingRedirects()->get('/calendar/month');
        $response->assertStatus(200);
    }

    public function test_day_page_is_accessable()
    {
        $response = $this->followingRedirects()->get('/calendar/today');
        $response->assertStatus(200);
    }

    public function test_year_page_is_accessable()
    {
        $response = $this->followingRedirects()->get('/calendar/year');
        $response->assertStatus(200);
    }
}
