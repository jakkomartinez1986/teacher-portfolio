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
        Schema::create('grade_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained();
            $table->foreignId('grade_id')->constrained();
            $table->foreignId('trimester_id')->constrained();
            $table->foreignId('student_id')->constrained('users');
            $table->decimal('formative_avg', 5, 2);
            $table->decimal('summative_exam', 5, 2);
            $table->decimal('summative_project', 5, 2);
            $table->decimal('final_grade', 5, 2);
            $table->text('comments')->nullable();
            $table->boolean('approved')->default(false);
            $table->timestamps();
            $table->unique(['student_id', 'subject_id', 'trimester_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_summaries');
    }
};
