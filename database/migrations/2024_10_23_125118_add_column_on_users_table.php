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
            $table->foreignId('lib_school_id')->after('address');
            $table->boolean('scholar_flag')->default(false)->after('lib_school_id');
            $table->boolean('shiftee_flag')->default(false)->after('scholar_flag');
            $table->boolean('irregular_flag')->default(false)->after('shiftee_flag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lib_school_id']);
            $table->dropColumn('lib_school_id');
            $table->dropColumn('scholar_flag');
            $table->dropColumn('shiftee_flag');
            $table->dropColumn('irregular_flag');
        });
    }
};
