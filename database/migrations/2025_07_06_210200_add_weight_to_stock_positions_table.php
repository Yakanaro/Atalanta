<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_positions', 'weight')) {
                $table->decimal('weight', 10, 2)->nullable()->after('thickness');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_positions', 'weight')) {
                $table->dropColumn('weight');
            }
        });
    }
};
