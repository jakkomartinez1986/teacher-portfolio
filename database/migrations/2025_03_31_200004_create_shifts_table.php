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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('year_id');
            $table->enum('shift_name', ['MATUTINA', 'VESPERTINA', 'INTENSIVO'])->comment('MATUTINA, VESPERTINA, INTENSIVO');
            $table->integer('status')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('year_id')->references('id')->on('years')->onUpdate('cascade')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
