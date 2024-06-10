<?php

namespace Tests\Feature;

use App\Models\School;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class SchoolControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private function createSchool($attributes = [])
    {
        return School::factory()->create($attributes);
    }

    private function assertJsonStructureSchool($response)
    {
        $response->assertJsonStructure([
            'school' => ['id', 'name', 'address', 'created_at', 'updated_at']
        ]);
    }

    public function test_can_get_all_schools()
    {
       
        $this->withoutMiddleware();

        // Créer 2 écoles avec des données aléatoires
        School::factory()->count(2)->create();
        
        // Appeler l'API pour obtenir la liste des écoles
        $response = $this->get('/api/v1/schools');

        // Vérifier que la réponse est OK
        $response->assertStatus(Response::HTTP_OK);

        // Vérifier la structure de la réponse JSON
        $response->assertJsonStructure([
            'schools' => [
                '*' => ['id', 'name', 'address', 'created_at', 'updated_at']
            ]
        ]);
    }

    public function test_can_create_school()
    {
        $this->withoutMiddleware();

        // Créer une école
        $school = $this->createSchool();

        // Appeler l'API pour créer une école
        $response = $this->post('/api/v1/schools', $school->toArray());

        // Vérifier que la réponse est OK
        $response->assertStatus(Response::HTTP_CREATED);

        // Vérifier la structure de la réponse JSON
        $this->assertJsonStructureSchool($response);
    }

    public function test_can_update_school()
    {
        $this->withoutMiddleware();

        // Créer une école
        $school = $this->createSchool();

        // Modifier l'école
        $updatedSchool = $this->createSchool();

        // Appeler l'API pour mettre à jour l'école
        $response = $this->put("/api/v1/schools/$school->id", $updatedSchool->toArray());

        // Vérifier que la réponse est OK
        $response->assertStatus(Response::HTTP_OK);

        // Vérifier la structure de la réponse JSON
        $this->assertJsonStructureSchool($response);
    }

    public function test_can_get_one_school()
    {
        $this->withoutMiddleware();

        // Créer une école
        $school = $this->createSchool();

        // Appeler l'API pour obtenir une école
        $response = $this->get("/api/v1/schools/$school->id");

        // Vérifier que la réponse est OK
        $response->assertStatus(Response::HTTP_OK);

        // Vérifier la structure de la réponse JSON
        $this->assertJsonStructureSchool($response);
    }

    public function test_can_delete_school()
    {
        $this->withoutMiddleware();

        // Créer une école
        $school = $this->createSchool();

        // Appeler l'API pour supprimer une école
        $response = $this->delete("/api/v1/schools/$school->id");

        // Vérifier que la réponse est OK
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
