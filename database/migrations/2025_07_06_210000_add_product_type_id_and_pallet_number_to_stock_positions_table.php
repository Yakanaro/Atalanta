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
        Schema::table('stock_positions', function (Blueprint $table) {
            // Добавляем внешней ключ product_type_id, если колонки ещё нет
            if (!Schema::hasColumn('stock_positions', 'product_type_id')) {
                $table->foreignId('product_type_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('product_types')
                    ->onDelete('set null');
            }

            // Добавляем pallet_number, если колонки ещё нет
            if (!Schema::hasColumn('stock_positions', 'pallet_number')) {
                $table->string('pallet_number')
                    ->nullable()
                    ->after('quantity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_positions', 'product_type_id')) {
                $table->dropForeign(['product_type_id']);
                $table->dropColumn('product_type_id');
            }

            if (Schema::hasColumn('stock_positions', 'pallet_number')) {
                $table->dropColumn('pallet_number');
            }
        });
    }
};
