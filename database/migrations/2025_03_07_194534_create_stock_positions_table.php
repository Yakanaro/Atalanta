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
        Schema::create('stock_positions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->decimal('length', 8, 2);
            $table->decimal('width', 8, 2);
            $table->decimal('thickness', 8, 2);
            $table->integer('quantity');
            $table->string('polish_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_positions');
    }
};
