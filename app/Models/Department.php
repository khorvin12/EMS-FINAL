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
        'manager_id'
    ];

    /**
     * Get all users (employees) in this department.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the manager of this department.
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * Get the count of employees in this department.
     */
    public function employeesCount()
    {
        return $this->users()->count();
    }
}