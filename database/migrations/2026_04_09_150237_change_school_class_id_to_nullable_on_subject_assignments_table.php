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
        // Add relationship to Subjects table
        if (!Schema::hasColumn('subject_assignments', 'subject_id')) {
            $table->foreignId('subject_id')->nullable()->after('user_id')->constrained()->cascadeOnDelete();
        }

        // Add relationship to School Classes table
        if (!Schema::hasColumn('subject_assignments', 'school_class_id')) {
            $table->foreignId('school_class_id')->nullable()->after('subject_id')->constrained()->nullOnDelete();
        }
    });
}

public function down(): void
{
    Schema::table('subject_assignments', function (Blueprint $table) {
        $table->dropForeign(['subject_id']);
        $table->dropForeign(['school_class_id']);
        $table->dropColumn(['subject_id', 'school_class_id']);
    });
}
};
