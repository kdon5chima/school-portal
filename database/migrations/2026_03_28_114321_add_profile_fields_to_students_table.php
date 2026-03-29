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
    Schema::table('students', function (Blueprint $table) {
        // Only adding the missing email field from the report card
        $table->string('student_email')->nullable(); 
    });
}

public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn('student_email');
    });
}

    /**
     * Reverse the migrations.
     */
    
};
