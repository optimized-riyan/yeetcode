<?php

namespace Database\Factories;

use App\Models\Difficulty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Problem>
 */
class ProblemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence,
            'description' => fake()->paragraph(100),
            'tc_parameters' => 'length nums',
            'difficulty_id' => fake()->numberBetween(1, Difficulty::count()),
            'scaffholding' => fake()->paragraph(),
        ];
    }
}
