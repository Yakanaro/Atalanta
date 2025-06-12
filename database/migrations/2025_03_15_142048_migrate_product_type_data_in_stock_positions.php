<?php

use App\Models\ProductType;
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
        // TODO временно отключено для деплоя, будет переработано
        // Перенос данных из старого поля в новое
        // $stockPositions = StockPosition::whereNotNull('old_type')->get();
        // 
        // foreach ($stockPositions as $position) {
        //     // Ищем соответствующий тип продукции по имени
        //     $productType = ProductType::where('name', 'like', '%' . $position->old_type . '%')->first();
        //     
        //     if ($productType) {
        //         $position->product_type_id = $productType->id;
        //         $position->save();
        //     }
        // }
        // 
        // // Удаляем старое поле после переноса данных
        // Schema::table('stock_positions', function (Blueprint $table) {
        //     $table->dropColumn('old_type');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // TODO временно отключено
        // Добавляем старое поле обратно
        // Schema::table('stock_positions', function (Blueprint $table) {
        //     $table->string('old_type')->nullable()->after('id');
        // });
        // 
        // // Переносим данные обратно
        // $stockPositions = StockPosition::whereNotNull('product_type_id')->get();
        // 
        // foreach ($stockPositions as $position) {
        //     if ($position->product_type_id) {
        //         $productType = ProductType::find($position->product_type_id);
        //         if ($productType) {
        //             $position->old_type = $productType->name;
        //             $position->save();
        //         }
        //     }
        // }
    }
};
