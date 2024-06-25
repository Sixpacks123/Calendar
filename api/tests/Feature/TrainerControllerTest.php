<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Response;
use Spatie\Permission\Models\Role;

class TrainerControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();

        // Create the trainer role if it doesn't exist
        if (Role::where('name', 'trainer')->count() === 0) {
            Role::create(['name' => 'trainer']);
        }
    }

    public function test_can_create_trainer()
    {
        $trainerData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password',
        ];

        $response = $this->post('/api/v1/trainers', $trainerData);
        $response->assertStatus(201);
        $response->assertJsonFragment(['email' => $trainerData['email']]);
        $this->assertDatabaseHas('users', ['email' => $trainerData['email']]);
    }

    public function test_can_retrieve_trainers()
    {
        User::factory()->count(3)->create()->each(function ($user) {
            $user->assignRole('trainer');
        });

        $response = $this->get('/api/v1/trainers');
        dump($response->json());
        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            '*' => ['name','email', 'created_at','updated_at', 'ulid','avatar','has_password']
        ]);
    }

    public function test_can_get_one_trainer()
    {
        $trainer = User::factory()->create();
        $trainer->assignRole('trainer');

        $response = $this->get("/api/v1/trainers/{$trainer->id}");

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $trainer->id,
            'name' => $trainer->name,
            'email' => $trainer->email,
            'roles' => [
                [
                    'name' => 'trainer'
                ]
            ]
        ]);
    }

    public function test_can_update_trainer()
    {
        $trainer = User::factory()->create();
        $trainer->assignRole('trainer');

        $updatedData = [
            'name' => 'Updated Name',
            'email' => $this->faker->unique()->safeEmail,
        ];

        $response = $this->put("/api/v1/trainers/{$trainer->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $updatedData['name']]);
        $this->assertDatabaseHas('users', ['email' => $updatedData['email']]);
    }

    public function test_can_delete_trainer()
    {
        $trainer = User::factory()->create();
        $trainer->assignRole('trainer');

        $response = $this->delete("/api/v1/trainers/{$trainer->id}");
        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('users', ['id' => $trainer->id]);
    }
}
