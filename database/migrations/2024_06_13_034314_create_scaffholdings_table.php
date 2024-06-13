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
            $table->text('scaffholding');
            $table->unsignedBigInteger('problem_id');
            $table->foreign('problem_id')->references('id')->on('problems');
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
