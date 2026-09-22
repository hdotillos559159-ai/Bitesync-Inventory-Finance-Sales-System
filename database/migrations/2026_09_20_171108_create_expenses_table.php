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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();

            // User who recorded the expense.
            // This connects the expense to the existing users table.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Expense classification.
            $table->string('category', 100);

            // Expense amount.
            $table->decimal('amount', 12, 2);

            // Date when the expense occurred.
            $table->date('expense_date');

            // Optional description/details.
            $table->string('description', 500)->nullable();

            // Optional receipt/reference number.
            $table->string('reference_no', 100)->nullable();

            // Expense status.
            $table->string('status', 30)->default('Recorded');

            $table->timestamps();

            // Indexes for faster filtering and reporting.
            $table->index('category');
            $table->index('expense_date');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};