<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class VenueIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->venue = factory(App\Repositories\Venue\Venue::class)->make([
            // put fields here
        ]);
        $this->venueEdited = factory(App\Repositories\Venue\Venue::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/venues');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('venues');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/venues/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'venues', $this->venue->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'venues', $this->venue->toArray());

        $response = $this->actor->call('GET', '/venues/'.$this->venue->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('venue');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'venues', $this->venue->toArray());
        $response = $this->actor->call('PATCH', '/venues/1', $this->venueEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('venues', $this->venueEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'venues', $this->venue->toArray());

        $response = $this->call('DELETE', '/venues/'.$this->venue->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/venues');
    }

}
