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
        Schema::create('class_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('grade_id')->constrained()->onDelete('cascade');  
            $table->foreignId('trimester_id')->nullable()->constrained()->onDelete('cascade'); // Nullable para horarios que aplican a todos los trimestres         
            $table->enum('schedule_type', ['OFFICIAL', 'EVALUATION', 'TEST', 'MAKEUP']);
            $table->enum('day', ['LUNES', 'MARTES', 'MIÉRCOLES', 'JUEVES', 'VIERNES', 'SÁBADO']);
            $table->time('start_time');
            $table->time('end_time');
            $table->string('classroom')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
             // Índices
            $table->index(['schedule_type', 'trimester_id', 'is_active']);
            $table->index(['year_id', 'schedule_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedules');
    }
};
