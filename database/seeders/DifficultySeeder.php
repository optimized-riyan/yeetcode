<?php

namespace Database\Seeders;

use App\Models\Difficulty;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DifficultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Difficulty::create([
            'difficulty' => 'Easy'
        ]);
        Difficulty::create([
            'difficulty' => 'Medium'
        ]);
        Difficulty::create([
            'difficulty' => 'Hard'
        ]);
    }
}
