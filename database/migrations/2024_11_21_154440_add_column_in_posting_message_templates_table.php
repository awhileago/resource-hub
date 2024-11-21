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
        Schema::table('posting_message_templates', function (Blueprint $table) {
            $table->text('interview_message')->nullable()->after('posting_id'); // Template message
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posting_message_templates', function (Blueprint $table) {
            $table->dropColumn('interview_message');
        });
    }
};
