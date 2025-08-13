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
        Schema::create('calendar_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained()->onDelete('cascade');
            $table->foreignId('trimester_id')->constrained()->onDelete('cascade');
            $table->string('period');           // Ej: PRIMERO, SEGUNDO, TERCERO
            $table->date('date');               // Ej: 2025-09-01
            $table->string('month_name');       // Ej: SEPTIEMBRE
            $table->string('day_name');         // Ej: LUNES
            $table->integer('week')->nullable();            // Ej: 1, 2, etc.
            $table->integer('day_number')->nullable();      // Día lectivo progresivo
            $table->text('activity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calendar_days');
    }
};
