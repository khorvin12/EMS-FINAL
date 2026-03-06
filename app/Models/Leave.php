<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'type',
        'reason',
        'status',
        'admin_comment',
    ];

protected $casts = [
    'start_date' => 'datetime',
    'end_date'   => 'datetime',
    'created_at' => 'datetime',
];

    // Kept as user() so ManageLeavesController still works ($leave->user->name)
    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    // Also available as employee() for new code
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}