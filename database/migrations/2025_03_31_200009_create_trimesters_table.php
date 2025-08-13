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
        Schema::create('trimesters', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('year_id');
            $table->string('trimester_name')->comment('trimestres'); 
            $table->date('start_date')->comment('inicio trimestre');
            $table->date('end_date')->comment('fin trimestre'); 
            $table->integer('status')->default(0);
             $table->foreign('year_id')->references('id')->on('years')->onUpdate('cascade')->onDelete('no action');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trimesters');
    }
};
