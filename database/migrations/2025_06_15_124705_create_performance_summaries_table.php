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
        Schema::create('performance_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('grade_id')->constrained();
            $table->foreignId('trimester_id')->constrained();
            $table->foreignId('student_id')->constrained('users');
            // Evaluación Formativa (como JSON flexible)
            $table->json('formative_scores')->nullable(); // lecciones, tareas, etc. como array
           // Evaluación Sumativa
            $table->decimal('integral_project', 5, 2)->nullable(); // or "proyecto interdisiciplinar"
            $table->decimal('final_evaluation', 5, 2)->nullable();
           // Promedios y escala
            $table->decimal('formative_average', 5, 2)->nullable();
            $table->decimal('summative_average', 5, 2)->nullable();
            // $table->decimal('total_formative', 5, 2)->nullable();
            // $table->decimal('total_summative', 5, 2)->nullable();
            $table->decimal('final_grade', 5, 2)->nullable(); // o "nota final trimestre"
            $table->string('grade_scale')->nullable();// DA, AA, PA, NA
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_summaries');
    }
};
