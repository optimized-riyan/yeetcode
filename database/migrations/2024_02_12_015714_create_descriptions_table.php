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
        Schema::create('descriptions', function (Blueprint $table) {
            $table->foreignId('problem_id')->references('id')->on('problems')->cascadeOnDelete();
            $table->primary('problem_id');
            $table->text('brief');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descriptions');
    }
};
