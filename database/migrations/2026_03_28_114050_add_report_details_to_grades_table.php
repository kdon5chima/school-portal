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
    Schema::table('grades', function (Blueprint $table) {
        // Attendance Data 
        $table->integer('days_present')->default(0);
        $table->integer('days_absent')->default(0);
        $table->integer('total_school_days')->default(124);

        // Affective Skills (Rating 1-5) 
        $table->integer('punctuality')->default(0);
        $table->integer('attentiveness')->default(0);
        $table->integer('neatness')->default(0);
        $table->integer('honesty')->default(0);
        $table->integer('politeness')->default(0);

        // Psychomotor Skills (Rating 1-5) 
        $table->integer('handwriting')->default(0);
        $table->integer('sports')->default(0);
        $table->integer('public_speaking')->default(0);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            //
        });
    }
};
