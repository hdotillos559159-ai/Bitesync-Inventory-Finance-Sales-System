<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_remittances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->date('remittance_date');

            $table->decimal('expected_amount', 12, 2)
                ->default(0);

            $table->decimal('actual_amount', 12, 2)
                ->default(0);

            $table->decimal('variance', 12, 2)
                ->default(0);

            $table->string('reference_no', 100)
                ->nullable();

            $table->string('remarks', 500)
                ->nullable();

            $table->string('status', 30)
                ->default('Recorded');

            $table->timestamps();

            $table->index('remittance_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_remittances');
    }
};