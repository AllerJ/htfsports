<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class OwnerIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->owner = factory(App\Repositories\Owner\Owner::class)->make([
            // put fields here
        ]);
        $this->ownerEdited = factory(App\Repositories\Owner\Owner::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/owners');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('owners');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/owners/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'owners', $this->owner->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'owners', $this->owner->toArray());

        $response = $this->actor->call('GET', '/owners/'.$this->owner->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('owner');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'owners', $this->owner->toArray());
        $response = $this->actor->call('PATCH', '/owners/1', $this->ownerEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('owners', $this->ownerEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'owners', $this->owner->toArray());

        $response = $this->call('DELETE', '/owners/'.$this->owner->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/owners');
    }

}
