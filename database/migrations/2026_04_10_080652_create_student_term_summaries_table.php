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
    Schema::create('student_term_summaries', function (Blueprint $table) {
        $table->id();
        $table->string('admission_number');
        $table->string('academic_year');
        $table->string('term');
        $table->integer('total_school_days')->default(0);
        $table->integer('days_present')->default(0);
        $table->integer('days_absent')->default(0);
        $table->text('teacher_comment')->nullable();
        $table->text('principal_comment')->nullable();
        $table->timestamps();
        
        // Ensure one record per student per term
        $table->unique(['admission_number', 'academic_year', 'term'], 'student_term_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_term_summaries');
    }
};
