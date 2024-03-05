<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Testcase>
 */
class TestcaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'testcase' => fake()->sentence(2),
            'expected_output' => fake()->sentence(),
            'is_trivial' => false,
        ];
    }
}
