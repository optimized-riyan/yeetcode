<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Example>
 */
class ExampleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'input' => fake()->text,
            'output' => fake()->text,
            'explaination' => fake()->text,
        ];
    }

    public function withoutExplaination(): Factory {
        return $this->state(function (array $attributes) {
            return [
                'explaination' => null
            ];
        });
    }
}
