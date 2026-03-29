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
    Schema::create('school_classes', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique(); // e.g., "Primary 1" or "SS 1"
        $table->string('section')->nullable(); // e.g., "Junior", "Senior", "Nursery"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
