<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_an_event(): void
    {
        $user = User::factory()->create();
        $conferenceRoom = Location::factory()->create(['manager_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->post(route('events.store'), [
            'name' => 'Sprint Retrospective',
            'agenda' => 'Discuss what went wrong',
            'start' => Carbon::now()->addHour()->toDateTimeString(),
            'end' => Carbon::now()->addHours(2)->toDateTimeString(),
            'participants' => [$user->email],
            'location_id' => $conferenceRoom->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('events', [
            'name' => 'Sprint Retrospective',
            'agenda' => 'Discuss what went wrong',
            'location_id' => $conferenceRoom->id,
        ]);
    }

    public function test_it_refuses_event_with_lack_of_name(): void
    {
        $user = User::factory()->create();
        $conferenceRoom = Location::factory()->create(['manager_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->post(route('events.store'), [
            'agenda' => 'Void',
            'start' => Carbon::now()->toDateTimeString(),
            'end' => Carbon::now()->addHours(12)->toDateTimeString(),
            'participants' => [$user->email],
            'location_id' => $conferenceRoom->id,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_it_refuses_event_with_an_invalid_duration(): void
    {
        $user = User::factory()->create();
        $conferenceRoom = Location::factory()->create(['manager_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->post(route('events.store'), [
            'name' => 'Too Long Meeting',
            'agenda' => 'A lot of small-talks',
            'start' => Carbon::now()->toDateTimeString(),
            'end' => Carbon::now()->addHours(12)->toDateTimeString(),
            'participants' => [$user->email],
            'location_id' => $conferenceRoom->id,
        ], ['Accept' => 'application/json']);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['end']);
    }
}
