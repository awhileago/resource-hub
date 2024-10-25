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
        Schema::table('user_employments', function (Blueprint $table) {
            $table->dropColumn(['start_year', 'end_year']);
            $table->date('start_date')->after('position');
            $table->date('end_date')->nullable()->default(null)->after('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_employments', function (Blueprint $table) {
            $table->dropColumn(['start_date', 'end_date']);
            $table->year('start_year')->after('position');
            $table->year('end_year')->nullable(1)->default(null)->after('start_year');
        });
    }
};
