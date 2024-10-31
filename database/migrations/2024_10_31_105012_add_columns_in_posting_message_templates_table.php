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
            $table->dropColumn(['message', 'is_approved']);
            $table->text('mobile_message_approved')->nullable()->after('posting_id'); // Template message
            $table->text('mobile_message_rejected')->nullable()->after('mobile_message_approved'); // Template message
            $table->text('email_message_approved')->nullable()->after('mobile_message_rejected'); // Template message
            $table->text('email_message_rejected')->nullable()->after('email_message_approved'); // Template message
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posting_message_templates', function (Blueprint $table) {
            $table->dropColumn(['mobile_message_approved', 'mobile_message_rejected', 'email_message_approved', 'email_message_rejected']);
            $table->boolean('is_approved')->default(0)->after('posting_id');
            $table->text('message')->nullable(1)->after('is_approved'); // Template message
        });
    }
};
