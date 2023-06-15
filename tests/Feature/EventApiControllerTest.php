<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Lara\ClubEvent;
use Tests\TestCase;

use Lara\EventView;
use Lara\Section;

use Carbon\Carbon;
class EventApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testGetEventsForExistingSection()
    {


        // Create a section for testing
        $section = Section::create(['title' => 'Test-Section']);

        // Create event views for the section
        factory(ClubEvent::class,3)->create()->each(function( ClubEvent $clubEvent ) {
            $clubEvent->start = Carbon::now()->add(1,'day');
            $clubEvent->end = Carbon::now()->add(2,'day');
        });

        // Send a GET request to the API endpoint
        $response = $this->get('/api/events/' . $section->title);

        // Assert that the response has a 200 status code
        $response->assertStatus(200);

        // Assert that the response is in JSON format
        $response->assertHeader('Content-Type', 'application/json');

        // Assert that the response body contains the expected event data
        $response->assertJsonStructure([
            'events' => [
                '*' => [
                    'import_id',
                    'name',
                    'start',
                    'start_time',
                    'end',
                    'end_time',
                    'place',
                    'icon',
                    'color',
                    'preparation_time',
                    'marquee',
                    'link',
                    'cancelled',
                    'updated_on',
                ],
            ],
        ]);
    }

    public function testGetEventsForNonExistingSection()
    {
        // Send a GET request to the API endpoint with a non-existing section
        $response = $this->get('/api/events/NonExistingSection');

        // Assert that the response has a 404 status code
        $response->assertStatus(404);

        // Assert that the response body contains the "not found" message
        $response->assertSeeText('not found');
    }
}
