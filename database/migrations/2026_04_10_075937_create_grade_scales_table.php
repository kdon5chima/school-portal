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
    Schema::create('grade_scales', function (Blueprint $table) {
        $table->id();
        $table->string('grade_letter'); // e.g., A1
        $table->integer('min_score');   // e.g., 75
        $table->integer('max_score');   // e.g., 100
        $table->string('remark')->nullable(); // e.g., Excellent
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_scales');
    }
};
