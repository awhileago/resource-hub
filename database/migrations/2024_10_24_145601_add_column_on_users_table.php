<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('lib_education_level_id')->after('lib_school_id');
            $table->foreignId('lib_academic_program_id')->after('lib_education_level_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lib_education_level_id']);
            $table->dropColumn('lib_education_level_id');
            $table->dropForeign(['lib_academic_program_id']);
            $table->dropColumn('lib_academic_program_id');
        });
    }
};
