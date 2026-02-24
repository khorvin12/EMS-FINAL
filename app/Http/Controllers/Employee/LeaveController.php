<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveController extends Controller
{
    public function index()
    {
        // leaves.employee_id is already users.id (integer FK) — correct
        $leaves = Leave::where('employee_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($leave) {
                return [
                    'id'          => $leave->id,
                    'start_date'  => $leave->start_date->format('Y-m-d'),
                    'end_date'    => $leave->end_date->format('Y-m-d'),
                    'type'        => $leave->type,
                    'reason'      => $leave->reason,
                    'description' => $leave->description,
                    'status'      => $leave->status,
                    'created_at'  => $leave->created_at->format('Y-m-d'),
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
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'type'        => 'required|string|in:sick,vacation,emergency,personal',
            'reason'      => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // auth()->id() returns users.id — correct
        Leave::create([
            'employee_id' => auth()->id(),
            'start_date'  => $validated['start_date'],
            'end_date'    => $validated['end_date'],
            'type'        => $validated['type'],
            'reason'      => $validated['reason'],
            'description' => $validated['description'] ?? null,
            'status'      => 'pending',
        ]);

        return redirect('/employee/leaves')->with('success', 'Leave request submitted successfully');
    }

    public function show(Leave $leave)
    {
        // auth()->id() returns users.id — correct
        if ($leave->employee_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Employee/Leaves/View', [
            'leave' => [
                'id'          => $leave->id,
                'start_date'  => $leave->start_date->format('Y-m-d'),
                'end_date'    => $leave->end_date->format('Y-m-d'),
                'type'        => $leave->type,
                'reason'      => $leave->reason,
                'description' => $leave->description,
                'status'      => $leave->status,
                'created_at'  => $leave->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}