<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->unique()->after('name');
            }
            $table->string('email')->nullable()->change();
        });

        // Make email nullable without requiring doctrine/dbal
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');
        } elseif ($driver === 'sqlite') {
            // SQLite has limited ALTER TABLE; skip changing nullability to avoid breaking tests.
            // Email will remain required on SQLite; app logic will still accept null but DB may enforce not null in tests.
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL');
        } elseif ($driver === 'pgsql') {
            DB::statement('ALTER TABLE users ALTER COLUMN email SET NOT NULL');
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->dropUnique(['username']);
                $table->dropColumn('username');
            }
        });
    }
};
