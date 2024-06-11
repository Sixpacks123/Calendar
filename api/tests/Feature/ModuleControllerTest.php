<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Module;
use App\Models\File;
use Illuminate\Support\Facades\Storage;


class ModuleControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    public function test_can_create_module()
    {
        Storage::fake('public');

        $moduleData = [
            'name' => $this->faker->sentence(),
            'hourly_rate' => $this->faker->randomFloat(2, 10, 100),
            'promotion' => $this->faker->name(),
            'comment' => $this->faker->paragraph(),
            'syllabus' => UploadedFile::fake()->create('syllabus.pdf'),
        ];

        $response = $this->postJson('/api/v1/modules', $moduleData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'module' => [
                'name',
                'hourly_rate',
                'promotion',
                'comment',
                'syllabus',
                'created_at',
                'updated_at',
                'id'
            ]
        ]);
    }

    public function test_can_retrieve_modules()
    {
        Module::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/modules');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_update_module()
    {
        $module = Module::factory()->create();
        $updatedData = [
            'name' => 'Updated Module Name', // Ajoutez le champ 'name' mis à jour
            'hourly_rate' => 50.00,
            'promotion' => '20',
            'comment' => 'Updated module comment',
        ];
    
        $response = $this->putJson("/api/v1/modules/{$module->id}", $updatedData);
    
        $response->assertJsonFragment(['message' => 'Module updated successfully']);

    
    }
    
    
    public function test_can_delete_module()
    {
        $module = Module::factory()->create();
        
    
        $response = $this->deleteJson("/api/v1/modules/{$module->id}");
    
        $response->assertStatus(204);
    }
    
    

}
