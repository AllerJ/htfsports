<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduleIntegrationTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function setUp()
    {
        parent::setUp();

        $this->schedule = factory(App\Repositories\Schedule\Schedule::class)->make([
            // put fields here
        ]);
        $this->scheduleEdited = factory(App\Repositories\Schedule\Schedule::class)->make([
            // put fields here
        ]);
        $user = factory(App\Repositories\User\User::class)->make();
        $this->actor = $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->actor->call('GET', '/schedules');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('schedules');
    }

    public function testCreate()
    {
        $response = $this->actor->call('GET', '/schedules/create');
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testStore()
    {
        $response = $this->actor->call('POST', 'schedules', $this->schedule->toArray());

        $this->assertEquals(302, $response->getStatusCode());
    }

    public function testEdit()
    {
        $this->actor->call('POST', 'schedules', $this->schedule->toArray());

        $response = $this->actor->call('GET', '/schedules/'.$this->schedule->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertViewHas('schedule');
    }

    public function testUpdate()
    {
        $this->actor->call('POST', 'schedules', $this->schedule->toArray());
        $response = $this->actor->call('PATCH', '/schedules/1', $this->scheduleEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('schedules', $this->scheduleEdited->toArray());
        $this->assertRedirectedTo('/');
    }

    public function testDelete()
    {
        $this->actor->call('POST', 'schedules', $this->schedule->toArray());

        $response = $this->call('DELETE', '/schedules/'.$this->schedule->id.'/delete');
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertRedirectedTo('/schedules');
    }

}
