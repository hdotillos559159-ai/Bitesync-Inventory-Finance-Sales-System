<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_items', function (Blueprint $table) {

            $table->id();


            /*
            |--------------------------------------------------------------------------
            | PURCHASE
            |--------------------------------------------------------------------------
            */

            $table->foreignId('purchase_id')
                ->constrained('purchases')
                ->cascadeOnDelete();


            /*
            |--------------------------------------------------------------------------
            | INVENTORY ITEM
            |--------------------------------------------------------------------------
            */

            $table->foreignId('inventory_item_id')
                ->constrained('inventory_items')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PURCHASE ITEM DETAILS
            |--------------------------------------------------------------------------
            */

            $table->decimal('quantity', 12, 2);

            $table->decimal('unit_cost', 12, 2);

            $table->decimal('subtotal', 12, 2);


            /*
            |--------------------------------------------------------------------------
            | RECEIVING
            |--------------------------------------------------------------------------
            |
            | This allows partial receiving.
            |
            | Example:
            |
            | Ordered: 100
            | Received: 40
            | Remaining: 60
            |
            */

            $table->decimal('received_quantity', 12, 2)
                ->default(0);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};