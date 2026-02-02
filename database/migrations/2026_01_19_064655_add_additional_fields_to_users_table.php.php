<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }

            if (!Schema::hasColumn('users', 'department_id')) {
                $table->unsignedBigInteger('department_id')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->nullable()->after('department_id');
            }

            if (!Schema::hasColumn('users', 'hire_date')) {
                $table->date('hire_date')->nullable()->after('role_id');
            }

            if (!Schema::hasColumn('users', 'salary')) {
                $table->decimal('salary', 15, 2)->nullable()->after('hire_date');
            }

            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->nullable()->after('salary');
            }

            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->nullable()->unique()->after('id');
            }

            if (!Schema::hasColumn('users', 'dob')) {
                $table->date('dob')->nullable()->after('employee_id');
            }

            if (!Schema::hasColumn('users', 'gender')) {
                $table->string('gender')->nullable()->after('dob');
            }

            if (!Schema::hasColumn('users', 'civil_status')) {
                $table->string('civil_status')->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'phone')) {
                $table->dropColumn('phone');
            }

            if (Schema::hasColumn('users', 'department_id')) {
                $table->dropColumn('department_id');
            }

            if (Schema::hasColumn('users', 'role_id')) {
                $table->dropColumn('role_id');
            }

            if (Schema::hasColumn('users', 'hire_date')) {
                $table->dropColumn('hire_date');
            }

            if (Schema::hasColumn('users', 'salary')) {
                $table->dropColumn('salary');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }

            if (Schema::hasColumn('users', 'employee_id')) {
                $table->dropColumn('employee_id');
            }

            if (Schema::hasColumn('users', 'dob')) {
                $table->dropColumn('dob');
            }

            if (Schema::hasColumn('users', 'gender')) {
                $table->dropColumn('gender');
            }

            if (Schema::hasColumn('users', 'civil_status')) {
                $table->dropColumn('civil_status');
            }
        });
    }
};
