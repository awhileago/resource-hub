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
            $table->boolean('is_admin')->index()->default(0)->after('is_active');
            $table->string('address')->nullable()->after('password');
            $table->string('barangay_code')->index()->nullable()->after('address');
            $table->foreign('barangay_code')->references('psgc_10_digit_code')->on('barangays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'address']);
            $table->dropForeign(['barangay_code']);
            $table->dropColumn('barangay_code');
        });
    }
};
