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
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->foreignId('role_id')->constrained('roles');
            $table->integer('approval_order');
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
            
            $table->unique(['document_type_id', 'role_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};
