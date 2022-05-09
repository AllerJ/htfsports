<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class TeamIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->team = factory(App\Repositories\Team\Team::class)->make([
            // put fields here
        ]);
        $this->teamEdited = factory(App\Repositories\Team\Team::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/teams');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('teams');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/teams/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'teams', $this->team->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'teams', $this->team->toArray());

        $response = $this->actor->call('GET', '/teams/'.$this->team->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('team');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'teams', $this->team->toArray());
        $response = $this->actor->call('PATCH', '/teams/1', $this->teamEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('teams', $this->teamEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'teams', $this->team->toArray());

        $response = $this->call('DELETE', '/teams/'.$this->team->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/teams');
    }

}
