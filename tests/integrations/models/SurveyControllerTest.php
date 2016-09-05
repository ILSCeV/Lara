<?php
use Lara\Survey;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SurveyControllerTest extends TestCase
{
    // using DatabaseTransactions, so tests do not affect our DB
    use DatabaseTransactions;
    /** @test */
    function can_fake()
    {
        $faked = factory(Survey::class)->create();
    }
}
