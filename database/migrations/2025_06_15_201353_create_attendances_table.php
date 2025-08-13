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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('class_observation_id')->nullable()->constrained('class_observations')->onDelete('set null');
            $table->foreignId('class_schedule_id')->nullable()->constrained('class_schedules')->onDelete('set null');
            $table->date('date');
            $table->char('status', 2)->nullable()->comment('Null=Presente, A=Atraso, I=Injustificada, J=Justificada, AA=Abandono Aula,AI=Abandono Institucional, P=Permiso, N=Novedad Estudiante');
            $table->time('arrival_time')->nullable();
            $table->text('justification')->nullable();
            $table->string('justification_file_path')->nullable();
            $table->text('observation')->nullable(); // Cambiamos notes por observation específica
            $table->json('notification_data')->nullable();
            $table->timestamp('notification_sent_at')->nullable();
            $table->foreignId('recorded_by')->constrained('users');
           
      
            $table->timestamps();
            $table->softDeletes();
            
            // Índices optimizados
            $table->index('tutor_id');
            $table->index('recorded_by');
            $table->index(['student_id', 'date']);
            $table->index(['class_schedule_id', 'date']);
            $table->index(['class_observation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
