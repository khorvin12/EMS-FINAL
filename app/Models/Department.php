<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'manager_id',
    ];

    // Returns all employees in this department
    public function employees()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    // Returns the manager (a User) of this department
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employeesCount()
    {
        return $this->employees()->count();
    }
}
