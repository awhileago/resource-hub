<?php

namespace Database\Seeders;

use App\Models\Library\LibPostingCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibPostingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibPostingCategory::upsert([
            ['id' => 1, 'desc' => 'Part-Time Job'],
            ['id' => 2, 'desc' => 'Scholarship'],
        ], ['id']);
    }
}
