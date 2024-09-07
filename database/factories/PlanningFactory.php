<?php

namespace Database\Factories;

use App\Models\Planning;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlanningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Planning::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'cours_id' => $this->faker->numberBetween(13, 38),
            'date_debut' => $this->faker->dateTimeThisMonth(),
            'date_fin' => now(),
        ];
    }
}
