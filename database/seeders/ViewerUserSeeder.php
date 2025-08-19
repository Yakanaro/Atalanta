<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class ViewerUserSeeder extends Seeder
{
    public function run(): void
    {
        // Creates or updates a viewer user
        User::updateOrCreate(
            ['email' => 'viewer@example.com'],
            [
                'name' => 'Viewer',
                'password' => 'viewer12345', // hashed via cast
                'role' => 'viewer',
            ]
        );
    }
}
