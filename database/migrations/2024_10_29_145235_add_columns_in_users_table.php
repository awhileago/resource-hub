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
            $table->string('cor_url')->index()->after('barangay_code')->nullable();
            $table->string('grade_url')->index()->after('cor_url')->nullable();
            $table->string('photo_url')->index()->after('grade_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cor_url', 'grade_url', 'photo_url']);
        });
    }
};
