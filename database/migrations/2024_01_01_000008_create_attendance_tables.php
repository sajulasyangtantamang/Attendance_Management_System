<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // One "attendance" row = one taken session (class + subject + date + period)
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->foreignId('subject_id')->nullable()->constrained('subjects')->nullOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->date('date');
            $table->string('period')->nullable(); // e.g. "1st period"
            $table->timestamps();

            $table->unique(['class_id', 'subject_id', 'date', 'period'], 'attendance_session_unique');
        });

        // One "attendance_details" row = one student's status within that session
        Schema::create('attendance_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendance')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->enum('status', ['present', 'absent', 'leave', 'late', 'holiday'])->default('present');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['attendance_id', 'student_id'], 'attendance_details_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_details');
        Schema::dropIfExists('attendance');
    }
};
