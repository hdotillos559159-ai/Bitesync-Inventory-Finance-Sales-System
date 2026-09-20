<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Meat',
                'description' => 'Meat and poultry ingredients.',
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Fresh vegetables and produce.',
            ],
            [
                'name' => 'Dairy',
                'description' => 'Milk, cheese, butter, and other dairy products.',
            ],
            [
                'name' => 'Beverages',
                'description' => 'Coffee, milk, drinks, and beverage ingredients.',
            ],
            [
                'name' => 'Dry Goods',
                'description' => 'Dry and shelf-stable food ingredients.',
            ],
            [
                'name' => 'Sauces',
                'description' => 'Sauces, condiments, and flavoring ingredients.',
            ],
            [
                'name' => 'Packaging',
                'description' => 'Food containers, cups, bags, and packaging materials.',
            ],
            [
                'name' => 'Supplies',
                'description' => 'General operating and kitchen supplies.',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                [
                    'description' => $category['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}