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
        Schema::create('grading_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('year_id')->constrained();
            $table->decimal('formative_percentage', 5, 2); // 70.00%
            $table->decimal('summative_percentage', 5, 2); // 30.00%
            $table->decimal('exam_percentage', 5, 2)->default(20); // % del examen dentro del sumativo
            $table->decimal('project_percentage', 5, 2)->default(10); // % del proyecto dentro del sumativo
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grading_settings');
    }
};
