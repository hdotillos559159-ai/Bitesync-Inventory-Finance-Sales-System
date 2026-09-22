<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        /*
         * =========================================================
         * INVENTORY CATEGORIES
         * =========================================================
         */
        $inventoryCategories = [
            [
                'name' => 'Meat',
                'description' => 'Beef, pork, and other meat ingredients.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Poultry',
                'description' => 'Chicken and other poultry ingredients.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Fresh vegetables used in food preparation.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Fruits',
                'description' => 'Fresh fruits used in food and beverage preparation.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Dairy',
                'description' => 'Milk, cheese, butter, and other dairy ingredients.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Dry Goods',
                'description' => 'Rice, flour, sugar, grains, and other dry ingredients.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Sauces & Condiments',
                'description' => 'Sauces, dressings, seasonings, and condiments.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Beverage Ingredients',
                'description' => 'Coffee, syrups, powders, and ingredients used for beverages.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Packaging',
                'description' => 'Food containers, cups, wrappers, bags, and packaging materials.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
            [
                'name' => 'Supplies',
                'description' => 'General consumable supplies used in restaurant operations.',
                'type' => Category::TYPE_INVENTORY,
                'is_active' => true,
            ],
        ];

        /*
         * =========================================================
         * PRODUCT CATEGORIES
         * =========================================================
         */
        $productCategories = [
            [
                'name' => 'Burgers',
                'description' => 'Burger menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Chicken Meals',
                'description' => 'Chicken-based menu meals.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Rice Meals',
                'description' => 'Rice-based menu meals.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Pasta',
                'description' => 'Pasta-based menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Sides',
                'description' => 'Side dishes and add-on menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Snacks',
                'description' => 'Snack menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drink and beverage menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Dessert menu products.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
            [
                'name' => 'Combos',
                'description' => 'Combined meal and product offerings.',
                'type' => Category::TYPE_PRODUCT,
                'is_active' => true,
            ],
        ];

        /*
         * =========================================================
         * SAVE INVENTORY CATEGORIES
         * =========================================================
         */
        foreach ($inventoryCategories as $category) {
            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                ],
                [
                    'description' => $category['description'],
                    'type' => $category['type'],
                    'is_active' => $category['is_active'],
                ]
            );
        }

        /*
         * =========================================================
         * SAVE PRODUCT CATEGORIES
         * =========================================================
         */
        foreach ($productCategories as $category) {
            Category::updateOrCreate(
                [
                    'name' => $category['name'],
                ],
                [
                    'description' => $category['description'],
                    'type' => $category['type'],
                    'is_active' => $category['is_active'],
                ]
            );
        }
    }
}