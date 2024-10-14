<?php

namespace Database\Seeders;

use App\Models\Library\LibSchool;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LibSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LibSchool::upsert([
            ['id' => 1, 'desc' => 'AMA Computer College – Tarlac'],
            ['id' => 2, 'desc' => 'Camiling Colleges, Inc.'],
            ['id' => 3, 'desc' => 'Carthel Science Educational Foundation (CSEF), Inc.'],
            ['id' => 4, 'desc' => 'Central Luzon Doctors’ Hospital Educational Institution, Inc.'],
            ['id' => 5, 'desc' => 'Centro Colegio De Tarlac, Inc.'],
            ['id' => 6, 'desc' => 'CIT Colleges of Paniqui Foundation, Inc.'],
            ['id' => 7, 'desc' => 'Concepcion Holy Cross College, Inc.'],
            ['id' => 8, 'desc' => 'Dominican College of Tarlac, Inc.'],
            ['id' => 9, 'desc' => 'Fundamental Baptist College For Asians, Inc.'],
            ['id' => 10, 'desc' => 'Gerona Junior College, Inc.'],
            ['id' => 11, 'desc' => 'Golden Olympus Colleges, Inc.'],
            ['id' => 12, 'desc' => 'Interworld College of Science and Technology Foundation'],
            ['id' => 13, 'desc' => 'Interworld Colleges Foundation, Inc.'],
            ['id' => 14, 'desc' => 'OLRA College Foundation, Inc.'],
            ['id' => 15, 'desc' => 'Osias Colleges, Inc.'],
            ['id' => 16, 'desc' => 'Our Lady of Peace College Seminary'],
            ['id' => 17, 'desc' => 'PWU CDCEC Tarlac, Inc.'],
            ['id' => 18, 'desc' => 'St. Augustine Colleges Foundation, Inc.'],
            ['id' => 19, 'desc' => 'St. Paul Colleges Foundation Paniqui, Tarlac, Inc.'],
            ['id' => 20, 'desc' => 'St. Rose College Educational Foundation, Inc.'],
            ['id' => 21, 'desc' => 'STI College Tarlac'],
            ['id' => 22, 'desc' => 'Tarlac Agricultural University'],
            ['id' => 23, 'desc' => 'Tarlac Christian Colleges, Inc.'],
            ['id' => 24, 'desc' => 'Tarlac State University'],
            ['id' => 25, 'desc' => 'United School of Science & Technology (USST) Colleges, Inc.'],
            ['id' => 26, 'desc' => 'University of the Philippines Manila School of Health Sciences Extension Campus – Tarlac'],
        ], ['id']);

    }
}
