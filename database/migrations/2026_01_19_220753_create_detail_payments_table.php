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
        Schema::create('detail_payments', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['debt', 'additional_debt', 'additional_service']);
            $table->decimal('amount_payed', 10, 2)->nullable();
            $table->timestamps();

            $table->foreignId('payment_id')->constrained('payments');
            $table->foreignId('debt_id')->nullable()->constrained('debts');
            $table->foreignId('additional_debt_id')->nullable()->constrained('additional_debts');
            $table->foreignId('additional_service_id')->nullable()->constrained('additional_services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_payments');
    }
};
