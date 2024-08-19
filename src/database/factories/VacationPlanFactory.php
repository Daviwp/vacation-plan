<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\VacationPlan;

class VacationPlanFactory extends Factory
{
    protected $model = VacationPlan::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'date' => $this->faker->date,
            'location' => $this->faker->address,
            'participants' => $this->faker->words,
        ];
    }
}

