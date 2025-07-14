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
            $table->unsignedBigInteger('stone_type_id')->nullable()->after('product_type_id');
            $table->foreign('stone_type_id')->references('id')->on('stone_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_positions', function (Blueprint $table) {
            $table->dropForeign(['stone_type_id']);
            $table->dropColumn('stone_type_id');
        });
    }
};
