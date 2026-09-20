<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipe_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recipe_id')
                ->constrained('recipes')
                ->cascadeOnDelete();

            $table->foreignId('inventory_item_id')
                ->constrained('inventory_items')
                ->restrictOnDelete();

            $table->decimal('quantity', 12, 4);

            $table->timestamps();

            $table->unique([
                'recipe_id',
                'inventory_item_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipe_items');
    }
};