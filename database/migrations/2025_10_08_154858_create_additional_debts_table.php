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
        Schema::create('additional_debts', function (Blueprint $table) {
            $table->id();
            $table->enum('concept', ['INSTALACION', 'RECONEXION']);
            $table->decimal('amount_payed', 10, 2);
            $table->decimal('original_amount', 10, 2);
            $table->boolean('payed')->default(0);
            $table->timestamps();

            $table->foreignId('contract_id')->constrained('contracts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_debts');
    }
};
