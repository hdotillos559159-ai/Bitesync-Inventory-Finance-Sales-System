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
            ],
            [
                'name' => 'Gram',
                'abbreviation' => 'g',
            ],
            [
                'name' => 'Liter',
                'abbreviation' => 'L',
            ],
            [
                'name' => 'Milliliter',
                'abbreviation' => 'ml',
            ],
            [
                'name' => 'Piece',
                'abbreviation' => 'pc',
            ],
            [
                'name' => 'Bottle',
                'abbreviation' => 'bt',
            ],
            [
                'name' => 'Pack',
                'abbreviation' => 'pk',
            ],
            [
                'name' => 'Box',
                'abbreviation' => 'box',
            ],
        ];

        foreach ($units as $unit) {
            Unit::firstOrCreate(
                ['abbreviation' => $unit['abbreviation']],
                [
                    'name' => $unit['name'],
                    'is_active' => true,
                ]
            );
        }
    }
}