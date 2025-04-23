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
        Schema::create('nivels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shift_id');
            $table->string('nivel_name')->comment('Nivel academico Inicial,Educacion General Basica y Bachillerato');
            $table->integer('status')->default(0);             
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('shift_id')->references('id')->on('shifts')->onUpdate('cascade')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nivels');
    }
};
