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
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('document_categories');
            $table->string('name');
            $table->string('description')->nullable();
            $table->enum('frequency', ['TRIMESTRAL', 'ANUAL', 'OCASIONAL']);
            $table->boolean('requires_director')->default(false);
            $table->boolean('requires_vice_principal')->default(false);
            $table->boolean('requires_principal')->default(false);
            $table->boolean('requires_dece')->default(false); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_types');
    }
};
