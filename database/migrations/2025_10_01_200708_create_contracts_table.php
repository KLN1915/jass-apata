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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['new', 'existing']);
            $table->string('code', 10)->unique();
            $table->date('start_date');
            // $table->boolean('installed');
            $table->enum('status', ['ACTIVO', 'SUSPENDIDO']);
            $table->timestamps();

            $table->foreignId('direction_id')->nullable()->constrained('directions');
            // $table->foreignId('institution_id')->nullable()->constrained('institutions');
            $table->foreignId('service_id')->constrained('services');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
