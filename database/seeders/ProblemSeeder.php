<?php

namespace Database\Seeders;

use App\Models\Constraint;
use App\Models\Problem;
use App\Models\Description;
use App\Models\Difficulty;
use App\Models\Example;
use App\Models\Testcase;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $problems = Problem::factory(10)->afterCreating(function (Problem $problem) {
            $problem->testcases()->take(3)->get()->each(function (Testcase $testcase) {
                $testcase->is_trivial = true;
                $testcase->save();
            });

            Constraint::factory(3)->make()->each(function (Constraint $constraint) use ($problem) {
                $problem->constraints()->save($constraint);
                $constraint->save();
            });
            Example::factory(3)->make()->each(function (Example $example) use ($problem) {
                $problem->examples()->save($example);
                $example->save();
            });
        })->hasHints(fake()->numberBetween(1, 3))
        ->hasTestcases(20)
        ->create();

        foreach ($problems as $problem) {
            $problem->similarProblems()->attach(Problem::find(random_int(1, 20)));
            $problem->similarProblems()->attach(Problem::find(random_int(1, 20)));
        }
    }
}
