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
            $table->string('qr_code_path')->nullable()->after('polish_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropColumn('qr_code_path');
        });
    }
};
