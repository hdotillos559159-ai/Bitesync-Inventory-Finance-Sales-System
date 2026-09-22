<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('type')
                ->default('inventory')
                ->after('description')
                ->index();
        });

        /*
        |--------------------------------------------------------------------------
        | Existing Categories
        |--------------------------------------------------------------------------
        |
        | Your existing categories are inventory categories, so keep them
        | assigned to the inventory category type.
        |
        */

        DB::table('categories')->update([
            'type' => 'inventory',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Product Categories
        |--------------------------------------------------------------------------
        |
        | Add menu/product-specific categories.
        |
        */

        $productCategories = [
            [
                'name' => 'Burgers',
                'description' => 'Burger products and burger-based meals.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Chicken Meals',
                'description' => 'Chicken-based meals and meal combinations.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Rice Meals',
                'description' => 'Rice-based meals including beef, pork, and other rice meals.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Pasta',
                'description' => 'Pasta dishes and pasta-based meals.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Sides',
                'description' => 'Side dishes and add-on food items.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Snacks',
                'description' => 'Snack items and small food servings.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Beverages',
                'description' => 'Hot, cold, and specialty beverages.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Desserts',
                'description' => 'Desserts and sweet food products.',
                'type' => 'product',
                'is_active' => true,
            ],
            [
                'name' => 'Combos',
                'description' => 'Meal combinations containing multiple products.',
                'type' => 'product',
                'is_active' => true,
            ],
        ];

        foreach ($productCategories as $category) {
            $exists = DB::table('categories')
                ->where('name', $category['name'])
                ->exists();

            if (!$exists) {
                DB::table('categories')->insert([
                    ...$category,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('categories')
            ->where('type', 'product')
            ->delete();

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropColumn('type');
        });
    }
};