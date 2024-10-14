<?php

namespace Database\Seeders;

use App\Models\Library\LibYearLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibYearLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibYearLevel::upsert([
            ['id' => 1, 'desc' => 'First Year'],
            ['id' => 2, 'desc' => 'Second Year'],
            ['id' => 3, 'desc' => 'Third Year'],
            ['id' => 4, 'desc' => 'Fourth Year'],
            ['id' => 5, 'desc' => 'Fifth Year'],
            ['id' => 6, 'desc' => 'Sixth Year'],
        ], ['id']);
    }
}
