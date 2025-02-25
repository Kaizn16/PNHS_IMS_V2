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
        Schema::create('class_management', function (Blueprint $table) {
            $table->id('class_management_id');
            $table->string('class_name')->unique();
            $table->foreignId('room_id')->constrained('rooms', 'room_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('teacher_id')->constrained('teachers', 'teacher_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subject_id')->constrained('subjects', 'subject_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('year_level');
            $table->string('section');
            $table->string('semester');
            $table->string('school_year');
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_management');
    }
};
