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
            $table->boolean('no_scholar_flag')->default(false)->after('coordinates');
            $table->boolean('no_ofw_flag')->default(false)->after('no_scholar_flag');
            $table->boolean('no_shiftee_flag')->default(false)->after('no_ofw_flag');
            $table->boolean('no_irregular_flag')->default(false)->after('no_shiftee_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postings', function (Blueprint $table) {
            $table->dropColumn('no_scholar_flag');
            $table->dropColumn('no_ofw_flag');
            $table->dropColumn('no_shiftee_flag');
            $table->dropColumn('no_irregular_flag');
        });
    }
};
