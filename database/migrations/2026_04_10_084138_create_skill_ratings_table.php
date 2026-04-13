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
        Schema::create('skill_ratings', function (Blueprint $table) {
    $table->id();
    $table->string('admission_number');
    $table->foreignId('skill_id')->constrained()->onDelete('cascade');
    $table->integer('rating'); // 1, 2, 3, 4, or 5
    $table->string('academic_year');
    $table->string('term');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_ratings');
    }
};
