<?php

namespace Database\Factories;

use App\Models\Cours;
use Illuminate\Database\Eloquent\Factories\Factory;

class CoursFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cours::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'intitule' => $this->faker->word(),
            'user_id' => 1,
            'formation_id' => 1,
            'created_at' => now(),
            'updated_at' => now()

        ];
    }
}
