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
        Schema::create('student_records', function (Blueprint $table) {
            $table->id('student_record_id');
            $table->foreignId('academic_record_id')->constrained('academic_records', 'academic_record_id')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('student_id')->constrained('students', 'student_id')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('exam_type', ['Midterm', 'Final']);
            $table->decimal('grade',5,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_records');
    }
};
