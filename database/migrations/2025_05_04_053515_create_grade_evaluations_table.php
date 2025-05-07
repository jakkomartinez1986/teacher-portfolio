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
        Schema::create('grade_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('grade_id')->constrained();
            $table->foreignId('trimester_id')->constrained();
            $table->foreignId('student_id')->constrained('users');
            $table->foreignId('teacher_id')->constrained('users');
            $table->enum('type', ['formative', 'summative_exam', 'summative_project']);
            $table->foreignId('activity_id')->nullable()->constrained('formative_activities');
            $table->string('description');
            $table->decimal('value', 5, 2); // Nota con 2 decimales
            $table->date('date');
            $table->text('comments')->nullable();
            $table->timestamps();
            $table->index(['student_id', 'subject_id', 'trimester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_evaluations');
    }
};
