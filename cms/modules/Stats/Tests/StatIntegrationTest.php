<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StatIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->stat = factory(App\Repositories\Stat\Stat::class)->make([
            // put fields here
        ]);
        $this->statEdited = factory(App\Repositories\Stat\Stat::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/stats');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('stats');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/stats/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'stats', $this->stat->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'stats', $this->stat->toArray());

        $response = $this->actor->call('GET', '/stats/'.$this->stat->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('stat');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'stats', $this->stat->toArray());
        $response = $this->actor->call('PATCH', '/stats/1', $this->statEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('stats', $this->statEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'stats', $this->stat->toArray());

        $response = $this->call('DELETE', '/stats/'.$this->stat->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/stats');
    }

}
