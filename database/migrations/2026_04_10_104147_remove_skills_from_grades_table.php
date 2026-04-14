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
        /**
         * We are no longer dropping hardcoded columns here because 
         * they have been moved to the 'skills' and 'skill_ratings' tables.
         * * We also removed the 'academic_year' addition because it now lives 
         * in the primary 'create_grades_table' migration.
         */
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
