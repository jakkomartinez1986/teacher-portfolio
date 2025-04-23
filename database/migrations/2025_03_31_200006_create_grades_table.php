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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('nivel_id');
            $table->string('grade_name')->comment('grado o nivel ( 1ro , 2do, etc)');
            $table->string('section')->comment('paralelo A,B,C,D,E,F');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('nivel_id')->references('id')->on('nivels')->onUpdate('cascade')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
