<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Add role_id column to users
    Schema::table('users', function (Blueprint $table) {
        $table->unsignedBigInteger('role_id')->nullable()->after('role');
        
        // users.department_id → departments.id
        $table->foreign('department_id')
              ->references('id')
              ->on('departments')
              ->nullOnDelete();

        // users.role_id → roles.id
        $table->foreign('role_id')
              ->references('id')
              ->on('roles')
              ->nullOnDelete();
    });

    // departments.manager_id → users.id
    Schema::table('departments', function (Blueprint $table) {
        $table->foreign('manager_id')
              ->references('id')
              ->on('users')
              ->nullOnDelete();
    });

    // Sync role_id from role string
    DB::statement('UPDATE users SET role_id = (SELECT id FROM roles WHERE roles.name = users.role)');
}

    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            $table->dropForeign(['manager_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
        });
    }
};