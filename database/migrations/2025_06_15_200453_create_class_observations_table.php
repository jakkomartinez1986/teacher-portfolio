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
        Schema::create('class_observations', function (Blueprint $table) {
          
            $table->id();
            $table->foreignId('class_schedule_id')->nullable()->constrained();
            $table->foreignId('tutor_id')->constrained('users');
            $table->foreignId('teacher_id')->constrained('users');
            $table->date('observation_date');
            $table->string('classtopic');
            $table->text('observation');
            $table->timestamps();
            $table->index('tutor_id');
            $table->index('teacher_id');    
            $table->index(['class_schedule_id', 'observation_date']);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_observations');
    }
};
