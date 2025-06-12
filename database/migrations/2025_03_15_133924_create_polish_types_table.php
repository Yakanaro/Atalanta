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
        Schema::create('polish_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Название вида полировки');
            $table->boolean('is_active')->default(true)->comment('Активен ли вид полировки');
            $table->integer('sort_order')->default(0)->comment('Порядок сортировки');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polish_types');
    }
};
