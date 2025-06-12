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
            $table->renameColumn('polish_type', 'old_polish_type');
            
            // Добавляем новое поле для внешнего ключа
            $table->foreignId('polish_type_id')->nullable()->after('quantity')
                ->constrained('polish_types')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropForeign(['polish_type_id']);
            $table->dropColumn('polish_type_id');
            $table->renameColumn('old_polish_type', 'polish_type');
        });
    }
};
