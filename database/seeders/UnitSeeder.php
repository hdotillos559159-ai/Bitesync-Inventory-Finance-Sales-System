<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'name' => 'Kilogram',
                'abbreviation' => 'kg',
                'is_active' => true,
            ],
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
                'is_active' => true,
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'L',
                'is_active' => true,
            ],
            [
                'name' => 'Milliliter',
                'abbreviation' => 'mL',
                'is_active' => true,
            ],
            [
                'name' => 'Piece',
                'abbreviation' => 'pcs',
                'is_active' => true,
            ],
            [
                'name' => 'Pack',
                'abbreviation' => 'pack',
                'is_active' => true,
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'box',
                'is_active' => true,
            ],
            [
                'name' => 'Bottle',
                'abbreviation' => 'btl',
                'is_active' => true,
            ],
            [
                'name' => 'Can',
                'abbreviation' => 'can',
                'is_active' => true,
            ],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(
                ['name' => $unit['name']],
                [
                    'abbreviation' => $unit['abbreviation'],
                    'is_active' => $unit['is_active'],
                ]
            );
        }
    }
}