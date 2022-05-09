<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class GameIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->game = factory(App\Repositories\Game\Game::class)->make([
            // put fields here
        ]);
        $this->gameEdited = factory(App\Repositories\Game\Game::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/games');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('games');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/games/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'games', $this->game->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'games', $this->game->toArray());

        $response = $this->actor->call('GET', '/games/'.$this->game->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('game');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'games', $this->game->toArray());
        $response = $this->actor->call('PATCH', '/games/1', $this->gameEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('games', $this->gameEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'games', $this->game->toArray());

        $response = $this->call('DELETE', '/games/'.$this->game->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/games');
    }

}
