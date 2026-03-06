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
        Schema::create('directions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedTinyInteger('cant_beneficiaries')->nullable();
            $table->unsignedTinyInteger('permanence')->nullable();
            $table->boolean('drains')->nullable();
            $table->enum('material',['RUSTICO','NOBLE','MIXTO'])->nullable();;
            $table->boolean('contracted')->default('0');
            $table->timestamps();

            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('directions');
    }
};
