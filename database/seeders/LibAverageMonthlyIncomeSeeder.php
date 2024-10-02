<?php

namespace Database\Seeders;

use App\Models\Library\LibAverageMonthlyIncome;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibAverageMonthlyIncomeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibAverageMonthlyIncome::upsert([
            ['id' => 1, 'desc' => 'Less than ₱10,000'],
            ['id' => 2, 'desc' => '₱10,000 - ₱19,999'],
            ['id' => 3, 'desc' => '₱20,000 - ₱29,999'],
            ['id' => 4, 'desc' => '₱30,000 - ₱39,999'],
            ['id' => 5, 'desc' => '₱40,000 - ₱49,999'],
            ['id' => 6, 'desc' => '₱50,000 - ₱59,999'],
            ['id' => 7, 'desc' => '₱60,000 - ₱69,999'],
            ['id' => 8, 'desc' => '₱70,000 - ₱79,999'],
            ['id' => 9, 'desc' => '₱80,000 - ₱89,999'],
            ['id' => 10, 'desc' => '₱90,000 - ₱99,999'],
            ['id' => 11, 'desc' => '₱100,000 - ₱149,999'],
            ['id' => 12, 'desc' => '₱150,000 - ₱199,999'],
            ['id' => 13, 'desc' => '₱200,000 - ₱249,999'],
            ['id' => 14, 'desc' => '₱250,000 - ₱299,999'],
            ['id' => 15, 'desc' => '₱300,000 and above'],
        ], ['id']);
    }
}
