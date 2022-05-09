<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class RosterIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->roster = factory(App\Repositories\Roster\Roster::class)->make([
            // put fields here
        ]);
        $this->rosterEdited = factory(App\Repositories\Roster\Roster::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/rosters');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('rosters');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/rosters/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'rosters', $this->roster->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'rosters', $this->roster->toArray());

        $response = $this->actor->call('GET', '/rosters/'.$this->roster->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('roster');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'rosters', $this->roster->toArray());
        $response = $this->actor->call('PATCH', '/rosters/1', $this->rosterEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('rosters', $this->rosterEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'rosters', $this->roster->toArray());

        $response = $this->call('DELETE', '/rosters/'.$this->roster->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/rosters');
    }

}
