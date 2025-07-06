<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            if (Schema::hasColumn('stock_positions', 'type')) {
                $table->dropColumn('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            if (!Schema::hasColumn('stock_positions', 'type')) {
                $table->string('type')->nullable();
            }
        });
    }
};
