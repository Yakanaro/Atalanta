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
            // Переименовываем существующее поле, чтобы сохранить данные
            $table->renameColumn('type', 'old_type');
            
            // Добавляем новое поле для внешнего ключа
            $table->foreignId('product_type_id')->nullable()->after('old_type')
                ->constrained('product_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropForeign(['product_type_id']);
            $table->dropColumn('product_type_id');
            $table->renameColumn('old_type', 'type');
        });
    }
};
