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
        $table->dropColumn([
            'days_present', 'days_absent', 'total_school_days', 'punctuality',
            'attentiveness', 'neatness', 'honesty', 'politeness', 'handwriting',
            'sports', 'public_speaking', 'self_control', 'relationship_with_peers',
            'student_image', 'teacher_signature', 'principal_signature'
        ]);
        
        // While we are here, let's add the missing academic_year column!
        $table->string('academic_year')->after('term')->nullable();
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
