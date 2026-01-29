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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->boolean('nulled')->default(0);
            $table->string('titular', 200);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('contract_id')->constrained('contracts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
