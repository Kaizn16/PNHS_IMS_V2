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
        Schema::create('teacher_subject_handle', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('teachers', 'teacher_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_subject_handle');
    }
};
