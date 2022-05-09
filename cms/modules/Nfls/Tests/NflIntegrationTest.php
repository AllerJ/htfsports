<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NflIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->nfl = factory(App\Repositories\Nfl\Nfl::class)->make([
            // put fields here
        ]);
        $this->nflEdited = factory(App\Repositories\Nfl\Nfl::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/nfls');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('nfls');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/nfls/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'nfls', $this->nfl->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'nfls', $this->nfl->toArray());

        $response = $this->actor->call('GET', '/nfls/'.$this->nfl->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('nfl');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'nfls', $this->nfl->toArray());
        $response = $this->actor->call('PATCH', '/nfls/1', $this->nflEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('nfls', $this->nflEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'nfls', $this->nfl->toArray());

        $response = $this->call('DELETE', '/nfls/'.$this->nfl->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/nfls');
    }

}
