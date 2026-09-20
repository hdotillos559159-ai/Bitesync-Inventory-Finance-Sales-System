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
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | SUPPLIER
            |--------------------------------------------------------------------------
            */

            $table->foreignId('supplier_id')
                ->constrained('suppliers')
                ->restrictOnDelete();


            /*
            |--------------------------------------------------------------------------
            | PURCHASE INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('purchase_number')
                ->unique();

            $table->date('purchase_date');

            $table->date('expected_date')
                ->nullable();

            $table->string('status')
                ->default('Draft');


            /*
            |--------------------------------------------------------------------------
            | FINANCIAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->decimal('subtotal', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total', 12, 2)
                ->default(0);


            /*
            |--------------------------------------------------------------------------
            | NOTES
            |--------------------------------------------------------------------------
            */

            $table->text('notes')
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | USERS
            |--------------------------------------------------------------------------
            |
            | created_by  = User who created the purchase
            | approved_by = CEO/Admin who approved it
            |
            */

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('approved_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};