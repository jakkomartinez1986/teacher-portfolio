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
        Schema::table('users', function (Blueprint $table) {
             // Agregar la columna school_id como clave foránea
             $table->foreignId('school_id')
             ->nullable() // Permitir valores nulos (opcional)
             ->constrained('schools') // Referencia a la tabla schools
             ->onDelete('cascade'); // Eliminar usuarios si la schools se elimina

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
             // Eliminar la clave foránea y la columna school_id
             $table->dropForeign(['school_id']);
             $table->dropColumn('school_id');
 
        });
    }
};
