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
    Schema::create('grade_scores', function (Blueprint $table) {
        $table->id();
        $table->string('admission_number');
        $table->string('student_name');
        $table->string('class_level');
        $table->string('subject');
        $table->string('term');
        $table->string('academic_year');
        $table->float('ca_score')->default(0);
        $table->float('exam_score')->default(0);
        $table->float('total_score')->default(0);
        $table->string('grade_letter')->nullable(); 
        $table->timestamps();

        // Adding an index makes loading the broadsheet much faster as the database grows
        $table->index(['admission_number', 'subject', 'term'], 'lookup_idx');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_scores');
    }
};
