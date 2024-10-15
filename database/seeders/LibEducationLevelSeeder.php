<?php

namespace Database\Seeders;

use App\Models\Library\LibEducationLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibEducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibEducationLevel::upsert([
            ['id' => 1, 'desc' => 'Elementary'],
            ['id' => 2, 'desc' => 'Secondary'],
            ['id' => 3, 'desc' => 'College'],
            ['id' => 4, 'desc' => 'Vocational'],
        ], ['id']);
    }
}
