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
        Schema::table('postings', function (Blueprint $table) {
            $table->boolean('solo_parent_flag')->default(0)->after('no_irregular_flag');
            $table->boolean('pwd_flag')->default(0)->after('solo_parent_flag');
            $table->decimal('gwa',3,2)->after('pwd_flag')->nullable();
            $table->foreignId('lib_academic_program_id')->after('gwa')->nullable();
            $table->foreignId('lib_year_level_id')->after('lib_academic_program_id')->nullable();
            $table->foreignId('lib_average_monthly_income_id')->after('lib_year_level_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
