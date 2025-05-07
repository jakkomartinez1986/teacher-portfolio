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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('student_code')->nullable()->unique();// Código único del estudiante ->change()
            $table->foreignId('current_grade_id')->nullable()->constrained('grades');
            $table->tinyInteger('academic_status')->default(1); // 0=Inactivo, 1=Activo, 2=Graduado, etc.
            $table->date('enrollment_date');
            $table->json('additional_info')->nullable(); // Para datos adicionales flexibles
            $table->timestamps();
            
            // Índices adicionales
            $table->index('student_code');
            $table->index('academic_status');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
