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
        Schema::create('tc_parameters', function (Blueprint $table) {
            $table->foreignId('problem_id')->constrained()->cascadeOnDelete();
            $table->primary('problem_id');
            $table->string('params', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tc_parameters');
    }
};
