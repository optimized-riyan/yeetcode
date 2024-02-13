<?php

namespace Database\Seeders;

use App\Models\Example;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Example::factory()->count(15)->create();
        Example::factory()->count(5)->withoutExplaination()->create();
    }
}
