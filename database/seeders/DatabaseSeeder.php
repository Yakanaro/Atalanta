<?php

namespace Database\Seeders;

use App\Models\StockPosition;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // $this->call([
        //     PolishTypeSeeder::class,
        //     ProductTypeSeeder::class,
        // ]);

        // Create default admin user
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('secret'),
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            ViewerUserSeeder::class,
        ]);

        // StockPosition::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
