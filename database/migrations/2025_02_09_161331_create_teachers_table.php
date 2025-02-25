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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id('teacher_id');
            $table->foreignId('user_id')->nullable()->constrained('users', 'user_id')->onDelete('set null')->onUpdate('set null');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->enum('sex', ['Male', 'Female']);
            $table->string('civil_status');
            $table->date('date_of_birth');
            $table->string('nationality');
            $table->foreignId('province_id')->constrained('provinces', 'province_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('municipality_id')->constrained('municipalities', 'municipality_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('barangay_id')->constrained('barangays', 'barangay_id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('street_address')->nullable();
            $table->enum('designation', ['Adviser', 'Teacher']);
            $table->enum('employment_type', ['Part-Time', 'Full-Time']);
            $table->date('date_hired');
            $table->enum('employment_status', ['Active', 'Inactive']);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
