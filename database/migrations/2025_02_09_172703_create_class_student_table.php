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
        Schema::create('class_student', function (Blueprint $table) {
            $table->id('class_student_id');
            $table->foreignId('class_management_id')
                ->constrained('class_management', 'class_management_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('student_id')->nullable()->constrained('students', 'student_id')->onDelete('set null')->onUpdate('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_student');
    }
};
