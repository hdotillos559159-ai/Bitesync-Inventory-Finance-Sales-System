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
        Schema::create('stock_receipt_items', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Parent Receipt
            |--------------------------------------------------------------------------
            */

            $table->foreignId('stock_receipt_id')
                ->constrained('stock_receipts')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Inventory Item
            |--------------------------------------------------------------------------
            */

            $table->foreignId('inventory_item_id')
                ->constrained('inventory_items')
                ->restrictOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Received Quantity
            |--------------------------------------------------------------------------
            */

            $table->decimal('quantity', 12, 2);

            /*
            |--------------------------------------------------------------------------
            | Price Information
            |--------------------------------------------------------------------------
            |
            | unit_price = price paid for one unit
            | total_amount = quantity × unit_price
            |
            */

            $table->decimal('unit_price', 12, 2);

            $table->decimal('total_amount', 12, 2);

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('stock_receipt_id');
            $table->index('inventory_item_id');

            /*
            |--------------------------------------------------------------------------
            | Prevent Duplicate Inventory Lines
            |--------------------------------------------------------------------------
            |
            | A receipt should contain one line per inventory item.
            |
            */

            $table->unique([
                'stock_receipt_id',
                'inventory_item_id',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_receipt_items');
    }
};