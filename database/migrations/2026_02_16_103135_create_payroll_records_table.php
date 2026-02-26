<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('month'); // e.g. "February 2026"
            $table->integer('year')->nullable();
            $table->decimal('basic_salary', 10, 2)->default(0);
            $table->decimal('deductions', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('absences')->default(0);
            $table->integer('late_count')->default(0);
            $table->integer('total_late_minutes')->default(0);
            $table->decimal('total_hours_worked', 8, 2)->default(0);
            $table->integer('expected_hours')->default(176);
            $table->decimal('undertime_hours', 8, 2)->default(0);
            $table->integer('total_working_days')->default(22);
            $table->decimal('absence_deduction', 10, 2)->default(0);
            $table->decimal('late_deduction', 10, 2)->default(0);
            $table->decimal('undertime_deduction', 10, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);   // ← added
            $table->decimal('overtime_pay', 10, 2)->default(0);    // ← added
            $table->unsignedBigInteger('generated_by')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'month']);
            $table->foreign('generated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};