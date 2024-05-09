<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Meeting;
use App\Models\User;

use Illuminate\Http\Response;

class MeetingControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_can_create_meeting()
    {
    
        $meeting = Meeting::factory()->make([
            'start_hour' => now()->format('Y-m-d H:i:s'),
            'end_hour' => now()->addHour(1)->format('Y-m-d H:i:s')
        ]); // Assurez-vous que end_hour est après start_hour
    
        $response = $this->post('/api/v1/meetings', $meeting->toArray());
        $response->assertStatus(201);
        $response->assertJson($meeting->toArray());
    }
    
    public function test_can_retrieve_meetings()
    {
        Meeting::factory()->count(3)->create();

        $response = $this->get('/api/v1/meetings');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['id', 'start_hour', 'end_hour', 'break_time', 'location', 'school_id', 'admin_id', 'trainer_id']
        ]);
    }
    public function test_can_get_one_meeting()
    {
        $meeting = Meeting::factory()->create();

        $response = $this->get("/api/v1/meetings/{$meeting->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson($meeting->toArray());
    }
    public function test_can_update_meeting()
    {
        $meeting = Meeting::factory()->create();

        $updatedData = [
            'location' => 'Updated Location',
            'end_hour' => now()->addHours(2)->format('Y-m-d H:i:s')
        ];

        $response = $this->put("/api/v1/meetings/{$meeting->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment($updatedData);
    }

    public function test_can_delete_meeting()
    {
        $meeting = Meeting::factory()->create();

        $response = $this->delete("/api/v1/meetings/{$meeting->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
    public function test_can_assign_admin_to_meeting()
    {
        $admin = User::factory()->create();
        $meeting = Meeting::factory()->create();

        $response = $this->put("/api/v1/meetings/{$meeting->id}/assign-admin", [
            'admin_id' => $admin->id
        ]);
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Admin assigned successfully']);
    }

    public function test_can_assign_trainer_to_meeting()
    {
        $trainer = User::factory()->create();
        $meeting = Meeting::factory()->create();

        $response = $this->put("/api/v1/meetings/{$meeting->id}/assign-trainer", [
            'trainer_id' => $trainer->id
        ]);
        $response->dump();

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson(['message' => 'Trainer assigned successfully']);
    }

}
