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
        Schema::table('parent_information', function (Blueprint $table) {
            $table->boolean('solo_parent_flag')->default(0)->after('ofw_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parent_information', function (Blueprint $table) {
            $table->dropColumn('solo_parent_flag');
        });
    }
};
