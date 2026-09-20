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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('sale_number', 50)
                ->unique();

            $table->dateTime('sale_date');

            $table->decimal('subtotal', 12, 2)
                ->default(0);

            $table->decimal('discount', 12, 2)
                ->default(0);

            $table->decimal('tax', 12, 2)
                ->default(0);

            $table->decimal('total', 12, 2)
                ->default(0);

            $table->string('payment_method', 50)
                ->default('Cash');

            $table->decimal('amount_received', 12, 2)
                ->default(0);

            $table->decimal('change', 12, 2)
                ->default(0);

            $table->string('status', 30)
                ->default('Completed');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('sale_date');
            $table->index('status');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};