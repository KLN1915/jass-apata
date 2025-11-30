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
        Schema::create('debts', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('period')->unsigned();
            $table->enum('type', ['NORMAL', 'EXONERADO']);
            $table->decimal('amount', 10, 2);
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
        Schema::dropIfExists('debts');
    }
};
