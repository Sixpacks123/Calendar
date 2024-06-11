<?php
namespace Database\Factories;

use App\Models\Module;
use Illuminate\Database\Eloquent\Factories\Factory;

class ModuleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Module::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'hourly_rate' => $this->faker->randomFloat(2, 10, 100),
            'promotion' => $this->faker->name(),
            'comment' => $this->faker->paragraph(),
            'syllabus' => $this->faker->boolean(),
        ];
    }
}
