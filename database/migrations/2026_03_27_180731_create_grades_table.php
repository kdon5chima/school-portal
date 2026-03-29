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
            $table->string('student_name');
            $table->string('admission_number')->unique();
            $table->string('class_level'); // e.g., JSS1, SS3
            $table->string('term');        // e.g., 1st Term, 2nd Term
            $table->string('subject');     // e.g., Mathematics, English
            $table->integer('ca_score');   // Continuous Assessment (usually /40)
            $table->integer('exam_score'); // Exam Score (usually /60)
            $table->integer('total_score'); // Calculated (CA + Exam)
            $table->string('grade_letter'); // A, B, C, F
            $table->text('teacher_comment')->nullable();
            $table->timestamps();
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
