<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\File;

class FileFactory extends Factory
{

    public function definition()
    {
        return [
            'type' => $this->faker->word(),
            'link' => 'files/' . $this->faker->unique()->word() . '.pdf', // Simulated path
            'description' => $this->faker->sentence(),
            'module_id' => function () {
                return \App\Models\Module::factory()->create()->id;
            },
        ];
    }
}
