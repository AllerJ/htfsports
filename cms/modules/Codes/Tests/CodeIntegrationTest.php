<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CodeIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->code = factory(App\Repositories\Code\Code::class)->make([
            // put fields here
        ]);
        $this->codeEdited = factory(App\Repositories\Code\Code::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/codes');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('codes');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/codes/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'codes', $this->code->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'codes', $this->code->toArray());

        $response = $this->actor->call('GET', '/codes/'.$this->code->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('code');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'codes', $this->code->toArray());
        $response = $this->actor->call('PATCH', '/codes/1', $this->codeEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('codes', $this->codeEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'codes', $this->code->toArray());

        $response = $this->call('DELETE', '/codes/'.$this->code->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/codes');
    }

}
