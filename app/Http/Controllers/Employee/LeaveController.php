<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('employee_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->through(function ($leave) {
                return [
                    'id' => $leave->id,
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'reason' => $leave->reason,
                    'status' => $leave->status,
                    'created_at' => $leave->created_at->format('Y-m-d'),
                ];
            });

        return Inertia::render('Employee/Leaves/Index', [
            'leaves' => $leaves
        ]);
    }

    public function create()
    {
        return Inertia::render('Employee/Leaves/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|string|in:sick,vacation,emergency,personal',
            'reason' => 'required|string|max:255',
        ]);

        Leave::create([
            'employee_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);

        return redirect('/employee/leaves')->with('success', 'Leave request submitted successfully');
    }

    public function show(Leave $leave)
    {
        if ($leave->employee_id !== Auth::id()) {
            abort(403);
        }

        $leaveData = [
            'id' => $leave->id,
            'start_date' => $leave->start_date->format('Y-m-d'),
            'end_date' => $leave->end_date->format('Y-m-d'),
            'type' => $leave->type,
            'reason' => $leave->reason,
            'status' => $leave->status,
            'created_at' => $leave->created_at->format('Y-m-d H:i:s'),
        ];

        return Inertia::render('Employee/Leaves/View', [
            'leave' => $leaveData
        ]);
    }
}