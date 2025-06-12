<?php

use App\Models\PolishType;
use App\Models\StockPosition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Перенос данных из старого поля в новое
        $stockPositions = StockPosition::whereNotNull('old_polish_type')->get();
        
        foreach ($stockPositions as $position) {
            // Ищем соответствующий тип полировки по имени
            $polishType = PolishType::where('name', $position->old_polish_type)->first();
            
            if ($polishType) {
                $position->polish_type_id = $polishType->id;
                $position->save();
            }
        }
        
        // Удаляем старое поле после переноса данных
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropColumn('old_polish_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Добавляем старое поле обратно
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->string('old_polish_type')->nullable()->after('quantity');
        });
        
        // Переносим данные обратно
        $stockPositions = StockPosition::whereNotNull('polish_type_id')->get();
        
        foreach ($stockPositions as $position) {
            if ($position->polish_type_id) {
                $polishType = PolishType::find($position->polish_type_id);
                if ($polishType) {
                    $position->old_polish_type = $polishType->name;
                    $position->save();
                }
            }
        }
    }
};
