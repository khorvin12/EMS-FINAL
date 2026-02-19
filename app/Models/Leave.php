<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'reason',
        'description',
        'status',
        'admin_comment'
    ];

    protected $casts = [
    'start_date' => 'datetime',
    'end_date'   => 'datetime',
    'created_at' => 'datetime',
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
