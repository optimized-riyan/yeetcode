<?php

namespace Database\Seeders;

use App\Models\Problem;
use App\Models\Description;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Problem::factory(10)->afterCreating(function (Problem $problem) {
            $description = Description::factory()->makeOne();
            $description->brief = fake()->paragraph;
            $description->problem()->associate($problem);
            $description->save();
        })->hasHints(fake()->numberBetween(1, 3))->create();
    }
}
