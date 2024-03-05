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
        Schema::create('testcases', function (Blueprint $table) {
            $table->id();
            $table->text('testcase');
            $table->unsignedBigInteger('problem_id');
            $table->text('expected_output');
            $table->boolean('is_trivial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testcases');
    }
};
