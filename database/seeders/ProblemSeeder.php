<?php

namespace Database\Seeders;

use App\Models\Constraint;
use App\Models\Problem;
use App\Models\Description;
use App\Models\Example;
use Illuminate\Database\Seeder;

class ProblemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Problem::factory(20)->afterCreating(function (Problem $problem) {
            $description = Description::factory()->makeOne();
            $description->problem()->associate($problem);
            $description->save();

            Constraint::factory(3)->make()->each(function (Constraint $constraint) use ($description) {
                $description->constraints()->save($constraint);
                $constraint->save();
            });
            Example::factory(3)->make()->each(function (Example $example) use ($description) {
                $description->examples()->save($example);
                $example->save();
            });
        })->hasHints(fake()->numberBetween(1, 3))->create();
    }
}
