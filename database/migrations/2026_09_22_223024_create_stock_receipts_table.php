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
        Schema::create('stock_receipts', function (Blueprint $table) {
            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Source Information
            |--------------------------------------------------------------------------
            |
            | supplier = selected supplier
            | store    = manually entered store/source
            | other    = manually entered source
            |
            */

            $table->enum('source_type', [
                'supplier',
                'store',
                'other',
            ])->default('supplier');

            /*
            |--------------------------------------------------------------------------
            | Supplier
            |--------------------------------------------------------------------------
            |
            | Nullable because Store and Other sources do not require
            | a supplier record.
            |
            */

            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Manual Source Name
            |--------------------------------------------------------------------------
            |
            | Used when source_type is store or other.
            |
            */

            $table->string('source_name')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Receipt Information
            |--------------------------------------------------------------------------
            */

            $table->string('reference_number', 100);

            $table->date('receipt_date');

            $table->text('notes')
                ->nullable();

            /*
            |--------------------------------------------------------------------------
            | Receipt Total
            |--------------------------------------------------------------------------
            |
            | Calculated from all StockReceiptItem records.
            |
            */

            $table->decimal('total_amount', 12, 2)
                ->default(0);

            /*
            |--------------------------------------------------------------------------
            | User Who Created The Receipt
            |--------------------------------------------------------------------------
            */

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();


            /*
            |--------------------------------------------------------------------------
            | Indexes
            |--------------------------------------------------------------------------
            */

            $table->index('source_type');
            $table->index('receipt_date');
            $table->index('reference_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_receipts');
    }
};