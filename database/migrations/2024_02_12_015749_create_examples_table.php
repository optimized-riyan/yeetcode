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
        Schema::create('examples', function (Blueprint $table) {
            $table->primary('description_id');
            $table->foreignId('description_id')->constrained()->cascadeOnDelete();
            $table->text('input');
            $table->text('output');
            $table->text('explaination')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examples');
    }
};
