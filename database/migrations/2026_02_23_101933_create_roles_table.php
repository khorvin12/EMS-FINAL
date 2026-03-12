<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('permissions')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'admin',    'permissions' => 'manage_employees,manage_departments,manage_leaves,manage_attendance,manage_salary,manage_roles', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'hr',       'permissions' => 'manage_leaves,manage_attendance,manage_salary', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'employee', 'permissions' => 'view_attendance,view_salary,request_leave',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};