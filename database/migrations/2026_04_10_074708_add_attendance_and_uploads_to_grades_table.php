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
        if (!Schema::hasColumn('grades', 'total_school_days')) {
            $table->integer('total_school_days')->default(0)->nullable();
        }
        if (!Schema::hasColumn('grades', 'days_present')) {
            $table->integer('days_present')->default(0)->nullable();
        }
        if (!Schema::hasColumn('grades', 'days_absent')) {
            $table->integer('days_absent')->default(0)->nullable();
        }
        if (!Schema::hasColumn('grades', 'self_control')) {
            $table->integer('self_control')->default(0)->nullable();
        }
        if (!Schema::hasColumn('grades', 'relationship_with_peers')) {
            $table->integer('relationship_with_peers')->default(0)->nullable();
        }
        if (!Schema::hasColumn('grades', 'student_image')) {
            $table->string('student_image')->nullable();
        }
        if (!Schema::hasColumn('grades', 'teacher_signature')) {
            $table->string('teacher_signature')->nullable();
        }
        if (!Schema::hasColumn('grades', 'principal_signature')) {
            $table->string('principal_signature')->nullable();
        }
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
