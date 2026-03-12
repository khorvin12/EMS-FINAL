<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'check_in',
        'check_out',
        'status',
    ];

    protected $casts = [
        'date'      => 'date:Y-m-d',
        'check_in'  => 'datetime:H:i:s',
        'check_out' => 'datetime:H:i:s',
    ];

    // ============================================================
    // RELATIONSHIPS
    // ============================================================

    // Attendance belongs to an Employee (User)
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}