<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class LeagueIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->league = factory(App\Repositories\League\League::class)->make([
            // put fields here
        ]);
        $this->leagueEdited = factory(App\Repositories\League\League::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/leagues');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('leagues');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/leagues/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'leagues', $this->league->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'leagues', $this->league->toArray());

        $response = $this->actor->call('GET', '/leagues/'.$this->league->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('league');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'leagues', $this->league->toArray());
        $response = $this->actor->call('PATCH', '/leagues/1', $this->leagueEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('leagues', $this->leagueEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'leagues', $this->league->toArray());

        $response = $this->call('DELETE', '/leagues/'.$this->league->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/leagues');
    }

}
