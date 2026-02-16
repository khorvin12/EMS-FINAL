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
        Schema::create('payroll_records', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id');
            $table->integer('month');
            $table->integer('year');
            
            // Salary information
            $table->decimal('gross_salary', 10, 2);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            
            // Attendance data
            $table->integer('absences')->default(0);
            $table->integer('late_count')->default(0);
            $table->integer('total_late_minutes')->default(0);
            $table->decimal('total_hours_worked', 8, 2)->default(0);
            $table->integer('expected_hours')->default(176);
            $table->decimal('undertime_hours', 8, 2)->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('total_working_days')->default(22);
            
            // Deduction breakdown
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('late_deduction', 10, 2)->default(0);
            $table->decimal('undertime_deduction', 10, 2)->default(0);
            
            $table->timestamp('generated_at');
            $table->unsignedBigInteger('generated_by')->nullable(); // HR user who generated it
            $table->timestamps();
            
            // Composite unique key: one payroll per employee per month
            $table->unique(['employee_id', 'month', 'year']);
            
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_records');
    }
};