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
    Schema::table('subject_assignments', function (Blueprint $table) {
        // Drop the old text-based columns
        $table->dropColumn(['subject_name', 'class_name']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('subject_assignments', function (Blueprint $table) {
        // Re-add them as nullable if you ever need to rollback
        $table->string('subject_name')->nullable();
        $table->string('class_name')->nullable();
    });
}
};
