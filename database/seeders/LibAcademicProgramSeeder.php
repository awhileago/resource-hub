<?php

namespace Database\Seeders;

use App\Models\Library\LibAcademicProgram;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LibAcademicProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Temporarily disable foreign key checks
        Schema::disableForeignKeyConstraints();
        // Truncate the table
        LibAcademicProgram::truncate();
        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
        LibAcademicProgram::upsert([
            ['id' => 1, 'desc' => '2-year Associate in Computer Technology'],
            ['id' => 2, 'desc' => '2-year Practical Nursing'],
            ['id' => 3, 'desc' => 'Bachelor in Elementary Education'],
            ['id' => 4, 'desc' => 'Bachelor in Human Services'],
            ['id' => 5, 'desc' => 'Bachelor of Animal Science'],
            ['id' => 6, 'desc' => 'Bachelor of Arts in Communication'],
            ['id' => 7, 'desc' => 'Bachelor of Arts in Economics'],
            ['id' => 8, 'desc' => 'Bachelor of Arts in English'],
            ['id' => 9, 'desc' => 'Bachelor of Arts in English Language Studies'],
            ['id' => 10, 'desc' => 'Bachelor of Arts in Political Science'],
            ['id' => 11, 'desc' => 'Bachelor of Arts in Psychology'],
            ['id' => 12, 'desc' => 'Bachelor of Early Childhood Education'],
            ['id' => 13, 'desc' => 'Bachelor of Elementary Education'],
            ['id' => 14, 'desc' => 'Bachelor of Elementary Education (General Education)'],
            ['id' => 15, 'desc' => 'Bachelor of Fine Arts Major in Visual Communication'],
            ['id' => 16, 'desc' => 'Bachelor of Industrial Technology major in Automotive Technology'],
            ['id' => 17, 'desc' => 'Bachelor of Industrial Technology major in Electrical Technology'],
            ['id' => 18, 'desc' => 'Bachelor of Industrial Technology major in Mechatronics Technology'],
            ['id' => 19, 'desc' => 'Bachelor of Physical Education'],
            ['id' => 20, 'desc' => 'Bachelor of Public Administration'],
            ['id' => 21, 'desc' => 'Bachelor of Science in Accountancy'],
            ['id' => 22, 'desc' => 'Bachelor of Science in Accounting Information System'],
            ['id' => 23, 'desc' => 'Bachelor of Science in Accounting Technology'],
            ['id' => 24, 'desc' => 'Bachelor of Science in Agribusiness'],
            ['id' => 25, 'desc' => 'Bachelor of Science in Agricultural and Biosystems Engineering'],
            ['id' => 26, 'desc' => 'Bachelor of Science in Agriculture'],
            ['id' => 27, 'desc' => 'Bachelor of Science in Architecture'],
            ['id' => 28, 'desc' => 'Bachelor of Science in Artificial Intelligence'],
            ['id' => 29, 'desc' => 'Bachelor of Science in Blockchain Technology'],
            ['id' => 30, 'desc' => 'Bachelor of Science in Business Administration Major in Human Resource Management'],
            ['id' => 31, 'desc' => 'Bachelor of Science in Business Administration Major in Business Economics'],
            ['id' => 32, 'desc' => 'Bachelor of Science in Business Administration Major in Financial Management'],
            ['id' => 33, 'desc' => 'Bachelor of Science in Business Administration Major in Human Resource Management'],
            ['id' => 34, 'desc' => 'Bachelor of Science in Business Administration Major in Management Information System'],
            ['id' => 35, 'desc' => 'Bachelor of Science in Business Administration Major in Marketing Management'],
            ['id' => 36, 'desc' => 'Bachelor of Science in Business Administration Major in Operation Management'],
            ['id' => 37, 'desc' => 'Bachelor of Science in Chemistry'],
            ['id' => 38, 'desc' => 'Bachelor of Science in Civil Engineering'],
            ['id' => 39, 'desc' => 'Bachelor of Science in Computer Engineering'],
            ['id' => 40, 'desc' => 'Bachelor of Science in Computer Science'],
            ['id' => 41, 'desc' => 'Bachelor of Science in Criminology'],
            ['id' => 42, 'desc' => 'Bachelor of Science in Cybersecurity'],
            ['id' => 43, 'desc' => 'Bachelor of Science in Data Science'],
            ['id' => 44, 'desc' => 'Bachelor of Science in Development Communication'],
            ['id' => 45, 'desc' => 'Bachelor of Science in Electrical Engineering'],
            ['id' => 46, 'desc' => 'Bachelor of Science in Electronics'],
            ['id' => 47, 'desc' => 'Bachelor of Science in Electronics Engineering'],
            ['id' => 48, 'desc' => 'Bachelor of Science in Entrepreneurship'],
            ['id' => 49, 'desc' => 'Bachelor of Science in Environmental Science'],
            ['id' => 50, 'desc' => 'Bachelor of Science in Food Technology'],
            ['id' => 51, 'desc' => 'Bachelor of Science in Forestry'],
            ['id' => 52, 'desc' => 'Bachelor of Science in Geodetic Engineering'],
            ['id' => 53, 'desc' => 'Bachelor of Science in Hospitality Management'],
            ['id' => 54, 'desc' => 'Bachelor of Science in Hotel and Restaurant Management'],
            ['id' => 55, 'desc' => 'Bachelor of Science in Industrial Engineering'],
            ['id' => 56, 'desc' => 'Bachelor of Science in Information System'],
            ['id' => 57, 'desc' => 'Bachelor of Science in Information Systems specialized in Business Analytics'],
            ['id' => 58, 'desc' => 'Bachelor of Science in Information Technology'],
            ['id' => 59, 'desc' => 'Bachelor of Science in Information Technology specialized in Network Administration'],
            ['id' => 60, 'desc' => 'Bachelor of Science in Information Technology specialized in Technical Service Management'],
            ['id' => 61, 'desc' => 'Bachelor of Science in Information Technology specialized in Web and Mobile Application'],
            ['id' => 62, 'desc' => 'Bachelor of Science in Internal Auditing'],
            ['id' => 63, 'desc' => 'Bachelor of Science in Marine Transportation'],
            ['id' => 64, 'desc' => 'Bachelor of Science in Mathematics'],
            ['id' => 65, 'desc' => 'Bachelor of Science in Mechanical Engineering'],
            ['id' => 66, 'desc' => 'Bachelor of Science in Medical Technology'],
            ['id' => 67, 'desc' => 'Bachelor of Science in Nursing'],
            ['id' => 68, 'desc' => 'Bachelor of Science in Office Administration'],
            ['id' => 69, 'desc' => 'Bachelor of Science in Pharmacy'],
            ['id' => 70, 'desc' => 'Bachelor of Science in Physical Therapy'],
            ['id' => 71, 'desc' => 'Bachelor of Science in Psychology'],
            ['id' => 72, 'desc' => 'Bachelor of Science in Radiologic Technology'],
            ['id' => 73, 'desc' => 'Bachelor of Science in Real Estate Management'],
            ['id' => 74, 'desc' => 'Bachelor of Science in Respiratory Therapy'],
            ['id' => 75, 'desc' => 'Bachelor of Science in Social Work'],
            ['id' => 76, 'desc' => 'Bachelor of Science in Tourism Management'],
            ['id' => 77, 'desc' => 'Bachelor of Science in Virtual Education'],
            ['id' => 78, 'desc' => 'Bachelor of Secondary Education major in Computer Education'],
            ['id' => 79, 'desc' => 'Bachelor of Secondary Education major in English'],
            ['id' => 80, 'desc' => 'Bachelor of Secondary Education major in Filipino'],
            ['id' => 81, 'desc' => 'Bachelor of Secondary Education major in Mathematics'],
            ['id' => 82, 'desc' => 'Bachelor of Secondary Education Major in Science'],
            ['id' => 83, 'desc' => 'Bachelor of Secondary Education major in Sciences'],
            ['id' => 84, 'desc' => 'Bachelor of Secondary Education major in Social Studies'],
            ['id' => 85, 'desc' => 'Bachelor of Technical - Vocational Teacher Education major in Food Service Management'],
            ['id' => 86, 'desc' => 'Bachelor of Technology and Livelihood Education Major in Agri-Fishery Arts'],
            ['id' => 87, 'desc' => 'Bachelor of Technology and Livelihood Education Major in Home Economics'],
            ['id' => 88, 'desc' => 'Bachelor of Technology and Livelihood Education major in Industrial Arts'],
            ['id' => 89, 'desc' => 'Bachelor of Technology and Livelihood Education Major in Information Communication and Technology'],
            ['id' => 90, 'desc' => 'Caregiving'],
            ['id' => 91, 'desc' => 'Doctor of Veterinary Medicine'],
            ['id' => 92, 'desc' => 'Practical Nursing'],
        ], ['id']);
    }
}
