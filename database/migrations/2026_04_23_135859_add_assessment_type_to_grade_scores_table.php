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
    Schema::table('grade_scores', function (Blueprint $table) {
        // We place it after academic_year for a clean structure
        // Defaulting to 'Final Exam' ensures existing data isn't broken
        $table->string('assessment_type')->default('Final Exam')->after('academic_year');
    });
}

public function down(): void
{
    Schema::table('grade_scores', function (Blueprint $table) {
        $table->dropColumn('assessment_type');
    });
}
};
