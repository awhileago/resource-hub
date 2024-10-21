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
        Schema::create('posting_applications', function (Blueprint $table) {
            $table->ulid('id')->index()->primary();
            $table->foreignUlid('user_id')->index()->constrained();
            $table->foreignUlid('posting_id')->index()->constrained();
            $table->boolean('is_save')->index()->default(0);
            $table->boolean('is_applied')->index()->default(0);
            $table->date('date_applied')->index()->nullable();
            $table->boolean('is_approved')->index()->nullable()->default(null);
            $table->date('status_date')->index()->nullable();
            $table->text('remarks')->nullable();
            $table->string('updated_by_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('updated_by_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posting_applications');
    }
};
