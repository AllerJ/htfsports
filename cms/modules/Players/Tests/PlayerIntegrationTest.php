<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PlayerIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->player = factory(App\Repositories\Player\Player::class)->make([
            // put fields here
        ]);
        $this->playerEdited = factory(App\Repositories\Player\Player::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/players');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('players');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/players/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'players', $this->player->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'players', $this->player->toArray());

        $response = $this->actor->call('GET', '/players/'.$this->player->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('player');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'players', $this->player->toArray());
        $response = $this->actor->call('PATCH', '/players/1', $this->playerEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('players', $this->playerEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'players', $this->player->toArray());

        $response = $this->call('DELETE', '/players/'.$this->player->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/players');
    }

}
