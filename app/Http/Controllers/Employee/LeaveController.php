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
        $userId = Auth::id();

        $rankMap = Leave::where('employee_id', $userId)
            ->orderBy('id', 'desc')
            ->pluck('id')
            ->values()
            ->flip()
            ->map(fn($i) => $i + 1);

        $total = $rankMap->count();

        $query = Leave::where('employee_id', $userId)->orderBy('id', 'desc');

        if (request()->filled('search')) {
            $search = request('search');
            $matchingIds = $rankMap->filter(function ($ascSerial, $id) use ($total, $search) {
                $descSerial = $total - $ascSerial + 1;
                return str_contains((string) $descSerial, $search);
            })->keys();

            $query->where(function ($q) use ($search, $matchingIds) {
                $q->where('reason', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhereIn('id', $matchingIds);
            });
        }

        $leaves = $query->paginate(6)->withQueryString();

        $leaves->getCollection()->transform(function ($leave) use ($rankMap, $total) {
            return [
                'id' => $leave->id,
                'serial_no' => $total - $rankMap[$leave->id] + 1,
                'start_date' => $leave->start_date->format('Y-m-d'),
                'end_date' => $leave->end_date->format('Y-m-d'),
                'type' => $leave->type,
                'reason' => $leave->reason,
                'status' => $leave->status,
                'created_at' => $leave->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Employee/Leaves/Index', [
            'leaves' => $leaves,
            'filters' => request()->only(['search']),
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
            'reason' => 'nullable|string',
        ]);

        // auth()->id() returns users.id — correct
        Leave::create([
            'employee_id' => Auth::id(),
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'type' => $validated['type'],
            'reason' => $validated['reason'] ?? null,
            'status' => 'pending',
        ]);

        return redirect()->route('employee.leaves')->with('success', 'Leave request submitted successfully!');
    }

    public function show(Leave $leave)
    {
        // auth()->id() returns users.id — correct
        if ($leave->employee_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Employee/Leaves/View', [
            'leave' => [
                'id' => $leave->id,
                'start_date' => \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d'),
                'end_date' => \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d'),
                'type' => $leave->type,
                'reason' => $leave->reason,
                'status' => $leave->status,
                'created_at' => \Carbon\Carbon::parse($leave->created_at)->format('Y-m-d H:i:s'),
            ]
        ]);
    }
}