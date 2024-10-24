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
        Schema::create('otps', function (Blueprint $table) {
            $table->ulid('id')->index()->primary();
            $table->foreignUlid('user_id')->constrained()->onDelete('cascade'); // Foreign key to users table
            $table->string('otp_code'); // The OTP code
            $table->boolean('is_verified')->default(false); // To check if OTP is verified
            $table->timestamp('expires_at')->nullable(); // Add expiration timestamp
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
