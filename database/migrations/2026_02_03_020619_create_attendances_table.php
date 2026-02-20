<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id'); // stores the employee_id string e.g. "EMP001"
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['present', 'late', 'absent', 'on_leave'])->default('present');
            $table->integer('late_minutes')->default(0);
            $table->decimal('hours_worked', 5, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'date']); // one record per employee per day
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};