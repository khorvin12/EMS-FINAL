<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'deductions',
        'net_salary',
        'present_days',
        'absences',
        'late_count',
        'total_late_minutes',
        'total_hours_worked',
        'expected_hours',
        'undertime_hours',
        'total_working_days',
        'absence_deduction',
        'late_deduction',
        'undertime_deduction',
        'generated_by',
        'generated_at',
    ];

    // Returns the employee (User) this payroll belongs to
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'employee_id');
    }

    // Returns the HR user who generated this payroll
    public function generatedBy()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
