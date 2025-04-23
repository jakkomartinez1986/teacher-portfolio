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
        Schema::create('years', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id');
            $table->string('year_name')->comment('año academico');
            $table->date('start_date')->comment('inicio año academico');
            $table->date('end_date')->comment('fin año academico');
            $table->integer('status')->default(0)->comment('estado');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('school_id')->references('id')->on('schools')->onUpdate('cascade')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('years');
    }
};
