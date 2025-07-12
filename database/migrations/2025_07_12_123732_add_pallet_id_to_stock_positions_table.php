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
        // Шаг 1: Добавляем поле pallet_id в таблицу stock_positions
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->foreignId('pallet_id')
                ->nullable()
                ->after('id')
                ->constrained('pallets')
                ->onDelete('set null');
        });

        // Шаг 2: Мигрируем данные из pallet_number в таблицу pallets и связываем с позициями
        $this->migrateDataToPallets();

        // Шаг 3: Удаляем поле pallet_number из таблицы stock_positions
        Schema::table('stock_positions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_positions', 'pallet_number')) {
                $table->dropColumn('pallet_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Шаг 1: Добавляем обратно поле pallet_number
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->string('pallet_number')->nullable()->after('quantity');
        });

        // Шаг 2: Восстанавливаем данные из связанных поддонов
        $this->restoreDataFromPallets();

        // Шаг 3: Удаляем поле pallet_id
        Schema::table('stock_positions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_positions', 'pallet_id')) {
                $table->dropForeign(['pallet_id']);
                $table->dropColumn('pallet_id');
            }
        });
    }

    /**
     * Мигрируем данные из pallet_number в таблицу pallets и связываем с позициями.
     */
    private function migrateDataToPallets(): void
    {
        // Получаем все уникальные номера поддонов из stock_positions
        $palletNumbers = DB::table('stock_positions')
            ->select('pallet_number')
            ->whereNotNull('pallet_number')
            ->where('pallet_number', '!=', '')
            ->distinct()
            ->pluck('pallet_number');

        // Создаем записи в таблице pallets для каждого уникального номера
        foreach ($palletNumbers as $palletNumber) {
            $palletId = DB::table('pallets')->insertGetId([
                'number' => $palletNumber,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Обновляем все позиции с данным номером поддона
            DB::table('stock_positions')
                ->where('pallet_number', $palletNumber)
                ->update(['pallet_id' => $palletId]);
        }
    }

    /**
     * Восстанавливаем данные из связанных поддонов (для отката миграции).
     */
    private function restoreDataFromPallets(): void
    {
        // Получаем все позиции со связанными поддонами
        $stockPositions = DB::table('stock_positions')
            ->join('pallets', 'stock_positions.pallet_id', '=', 'pallets.id')
            ->select('stock_positions.id', 'pallets.number as pallet_number')
            ->get();

        // Восстанавливаем номера поддонов в позициях
        foreach ($stockPositions as $position) {
            DB::table('stock_positions')
                ->where('id', $position->id)
                ->update(['pallet_number' => $position->pallet_number]);
        }
    }
};
