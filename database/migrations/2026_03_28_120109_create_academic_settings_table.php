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
    Schema::create('academic_settings', function (Blueprint $table) {
        $table->id();
        $table->string('academic_year')->default('2025-2026'); // 
        $table->string('current_term')->default('1st Term');   // 
        $table->boolean('is_mid_term')->default(false);        // 
        $table->date('next_term_begins')->nullable();          // 
        $table->integer('total_school_days')->default(124);    // 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_settings');
    }
};
