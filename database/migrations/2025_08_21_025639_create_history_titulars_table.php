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
        Schema::create('history_titulars', function (Blueprint $table) {
            $table->id();
            $table->string('names_lastnames', 100);
            $table->string('dni', 8)->nullable();
            $table->boolean('is_current')->default(1);
            $table->timestamps();

            $table->foreignId('client_id')->constrained('clients');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_titulars');
    }
};
