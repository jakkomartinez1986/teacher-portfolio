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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('document_types');
            $table->foreignId('creator_id')->constrained('users');
            $table->foreignId('year_id')->constrained('years');
            $table->foreignId('trimester_id')->nullable()->constrained('trimesters');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('file_path');
            $table->enum('status', ['DRAFT', 'PENDING_REVIEW', 'APPROVED', 'REJECTED', 'ARCHIVED'])->default('DRAFT');
            $table->boolean('is_shared')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
