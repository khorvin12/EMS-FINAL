<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'civil_status',
        'department_id',
        'hire_date',
        'salary',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'dob'               => 'date',
            'hire_date'         => 'date',
            'salary'            => 'decimal:2',
        ];
    }

    /**
     * Makes $user->name still work everywhere in your existing code.
     * Automatically combines first_name + last_name.
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Returns the department this user belongs to
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Returns all leave requests for this user
    public function leaves()
    {
        return $this->hasMany(Leave::class, 'employee_id');
    }

    // Returns all payroll records for this user
    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'employee_id', 'employee_id');
    }
}