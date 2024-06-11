<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\School;
use App\Models\Module;

class MeetingFactory extends Factory
{
    public function definition()
    {
        return [
            'start_hour' => $this->faker->time(),
            'end_hour' => $this->faker->time(),
            'break_time' => $this->faker->numberBetween(5, 30),
            'location' => $this->faker->city(),
            'school_id' => School::factory(),
            'admin_id' => User::factory(),
            'trainer_id' => User::factory(),
            'module_id' => $this->faker->boolean(50) ? Module::factory() : null 

        ];
    }
}
