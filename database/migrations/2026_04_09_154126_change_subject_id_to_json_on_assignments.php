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
        // 1. Remove the foreign key constraint first
        $table->dropForeign(['subject_id']);

        // 2. Now change the column to JSON
        // We use boolean logic to ensure it's nullable
        $table->json('subject_id')->nullable()->change();
    });
}

public function down(): void
{
    Schema::table('subject_assignments', function (Blueprint $table) {
        // To reverse, we'd change it back to bigint and re-add foreign key
        $table->unsignedBigInteger('subject_id')->nullable()->change();
        $table->foreign('subject_id')->references('id')->on('subjects');
    });
}
};
