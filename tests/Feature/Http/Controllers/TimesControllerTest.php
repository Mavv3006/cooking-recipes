<?php

namespace Http\Controllers;

use App\Models\Times;
use App\Models\User;
use Database\Seeders\TimesUnitSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class TimesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_index_page()
    {
        $this->seed(TimesUnitSeeder::class);
        Times::factory()->count(3)->create();


        $response = $this
            ->actingAs(User::factory()->create())
            ->get(route('times.index'));

        $response->assertOk();
        $response->assertInertia(fn(AssertableInertia $page) => $page
            ->component('Times/Index')
            ->hasAll('times', 'timeUnits')
        );
    }

    public function test_create_new_time()
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->post(route('times.store'), ['name' => 'bla bla bla']);

        $response->assertRedirect('/');
        $this->assertCount(1, Times::all());
    }

    public function test_update_time()
    {
        $time = Times::factory()->create(['name' => 'test']);

        $response = $this
            ->actingAs(User::factory()->create())
            ->put(route('times.update', ['time' => $time->id]), ['name' => 'bla bla bla']);

        $response->assertRedirect('/');
        $this->assertCount(1, Times::all());
        $this->assertEquals('bla bla bla', Times::all()->first()->name);
    }

    public function test_delete_time()
    {
        $time = Times::factory()->create();

        $response = $this
            ->actingAs(User::factory()->create())
            ->delete(route('times.delete', ['time' => $time]));

        $response->assertRedirect('/');
        $this->assertCount(0, Times::all());
    }
}
