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
        Schema::create('postings', function (Blueprint $table) {
            $table->ulid('id')->index()->primary();
            $table->foreignUlid('user_id')->index()->constrained();
            $table->date('date_published')->index();
            $table->date('date_end')->index();
            $table->string('title')->index();
            $table->longText('description');
            $table->integer('slot');
            $table->string('address')->nullable();
            $table->string('barangay_code')->index()->nullable();
            $table->geography('coordinates', 'point', 4326);
            $table->timestamps();

            $table->foreign('barangay_code')->references('psgc_10_digit_code')->on('barangays');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postings');
    }
};
