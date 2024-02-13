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
        Problem::factory()->count(10)->hasHints(random_int(1, 3))->afterCreating(
            function (Problem $problem) {
                Description::factory()->count(1)->forProblem($problem)->create();
            }
        )->create();
    }
}
