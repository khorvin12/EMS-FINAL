<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // 👈 ADD THIS
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->through(function ($leave) {
                return [
                    'id' => $leave->id,
                    'start_date' => $leave->start_date->format('Y-m-d'),
                    'end_date' => $leave->end_date->format('Y-m-d'),
                    'reason' => $leave->reason,
                    'description' => $leave->description,
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
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'status' => 'pending'
        ]);

        return redirect('/employee/leaves')->with('success', 'Leave request submitted successfully');
    }

    public function show(Leave $leave)
    {
        if ($leave->user_id !== Auth::id()) {
            abort(403);
        }

        $leaveData = [
            'id' => $leave->id,
            'start_date' => $leave->start_date->format('Y-m-d'),
            'end_date' => $leave->end_date->format('Y-m-d'),
            'reason' => $leave->reason,
            'description' => $leave->description,
            'status' => $leave->status,
            'created_at' => $leave->created_at->format('Y-m-d H:i:s'),
        ];

        return Inertia::render('Employee/Leaves/View', [
            'leave' => $leaveData
        ]);
    }
}
