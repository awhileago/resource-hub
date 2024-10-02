<?php

namespace Database\Seeders;

use App\Models\Library\LibSuffixName;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibSuffixNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibSuffixName::upsert([
            ['code' => 'II', 'suffix_desc' => 'II', 'sequence' => 3],
            ['code' => 'III', 'suffix_desc' => 'III', 'sequence' => 4],
            ['code' => 'IV', 'suffix_desc' => 'IV', 'sequence' => 5],
            ['code' => 'JR', 'suffix_desc' => 'Jr.', 'sequence' => 2],
            ['code' => 'NA', 'suffix_desc' => 'Not Applicable', 'sequence' => 0],
            ['code' => 'SR', 'suffix_desc' => 'Sr.', 'sequence' => 1],
            ['code' => 'V', 'suffix_desc' => 'V', 'sequence' => 6],
            ['code' => 'VI', 'suffix_desc' => 'VI', 'sequence' => 7],
            ['code' => 'VII', 'suffix_desc' => 'VII', 'sequence' => 8],
            ['code' => 'VIII', 'suffix_desc' => 'VIII', 'sequence' => 9],
        ], ['code']);
    }
}
