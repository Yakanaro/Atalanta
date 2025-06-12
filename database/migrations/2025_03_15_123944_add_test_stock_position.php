<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Создаем тестовую запись с изображением
        DB::table('stock_positions')->insert([
            'type' => 'Тестовая позиция',
            'length' => 100,
            'width' => 50,
            'thickness' => 5,
            'quantity' => 1,
            'polish_type' => 'Тестовая полировка',
            'image_path' => 'position_images/test_image.svg',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Удаляем тестовую запись
        DB::table('stock_positions')->where('type', 'Тестовая позиция')->delete();
    }
};
