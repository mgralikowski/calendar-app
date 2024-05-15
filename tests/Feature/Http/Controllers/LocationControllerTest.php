<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LocationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_adds_a_conference_room(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'api')->postJson(route('locations.store'), [
            'name' => 'Golden Conference Room',
            'address' => 'Piotrkowska 696',
            'manager_id' => $user->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('locations', [
            'name' => 'Golden Conference Room',
            'address' => 'Piotrkowska 696',
            'manager_id' => $user->id,
        ]);
    }
}
