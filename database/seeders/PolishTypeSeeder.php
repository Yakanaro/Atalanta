<?php

namespace Database\Seeders;

use App\Models\PolishType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PolishTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $polishTypes = [
            [
                'name' => 'Одна сторона',
                'sort_order' => 10,
            ],
            [
                'name' => 'Две стороны',
                'sort_order' => 20,
            ],
            [
                'name' => 'Три стороны',
                'sort_order' => 30,
            ],
            [
                'name' => 'Четыре стороны',
                'sort_order' => 40,
            ],
        ];

        foreach ($polishTypes as $type) {
            PolishType::firstOrCreate(
                ['name' => $type['name']],
                ['sort_order' => $type['sort_order'], 'is_active' => true]
            );
        }
    }
}
