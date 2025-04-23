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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name_school'); // Nombre de la institución
            $table->string('distrit')->nullable(); // Datos de Distrito-Circuito-AMIE
            $table->string('location')->nullable(); // Ciudad-Provincia-Pais
            $table->string('address')->nullable(); // Dirección
            $table->string('phone')->max(10)->nullable(); // Teléfono
            $table->string('email')->nullable(); // Correo electrónico
            $table->string('website')->nullable(); // Sitio web
            $table->string('logo_path')->nullable(); // Ruta del logo

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
