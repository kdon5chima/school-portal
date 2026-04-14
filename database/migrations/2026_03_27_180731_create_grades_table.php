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
    Schema::create('grades', function (Blueprint $table) {
        $table->id();
        $table->string('admission_number');
        $table->string('student_name');
        $table->string('subject');
        $table->string('class_level');
        $table->string('term');
        $table->string('academic_year'); // <--- Ensure this is here!
        $table->float('ca_score')->default(0);
        $table->float('exam_score')->default(0);
        $table->float('total_score')->default(0);
        // ... any other fields ...
        $table->timestamps();

        // ADD THE UNIQUE CONSTRAINT HERE
        $table->unique(['admission_number', 'subject', 'term', 'academic_year'], 'grades_composite_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
