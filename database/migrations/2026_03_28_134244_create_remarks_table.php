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
    Schema::create('remarks', function (Blueprint $table) {
        $table->id();
        $table->string('content'); // e.g., "A very impressive performance. Keep it up!"
        $table->enum('type', ['Teacher', 'Principal']); // To separate the lists
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remarks');
    }
};
