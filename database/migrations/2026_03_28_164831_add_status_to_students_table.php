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
        // Change 'after' to a column you know exists, 
        // or just remove ->after(...) entirely to put it at the end.
        $table->string('status')->default('Active')->after('admission_number'); 
    });
}
public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
