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
        Schema::create('scaffholdings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('language_id');
            $table->text('scaffholding')->nullable();
            $table->foreignId('problem_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scaffholdings');
    }
};
