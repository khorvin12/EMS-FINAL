<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Inertia\Inertia;

class ManageLeavesController extends Controller
{
    /**
     * Display all leave requests with statistics
     */
    public function index()
    {
        $leaves = $this->getFormattedLeaves();
        $stats = $this->getLeaveStats();

        return Inertia::render('Admin/ManageLeaves/Index', [
            'leaves' => $leaves,
            'stats' => $stats
        ]);
    }

    /**
     * Show individual leave for review
     */
    public function review(Leave $leave)
    {
        return Inertia::render('Admin/ManageLeaves/LeavesReview', [
            'leave' => $this->formatLeaveData($leave)
        ]);
    }

    /**
     * Approve a leave request
     */
    public function approve(Leave $leave)
    {
        $leave->update(['status' => 'approved']);
        
        return redirect()
            ->route('admin.manageleaves.index')
            ->with('success', 'Leave approved successfully');
    }

    /**
     * Reject a leave request
     */
    public function reject(Leave $leave)
    {
        $leave->update(['status' => 'rejected']);
        
        return redirect()
            ->route('admin.manageleaves.index')
            ->with('success', 'Leave rejected successfully');
    }

    /**
     * Get leave statistics for AJAX requests
     */
    public function getStats()
    {
        return response()->json($this->getLeaveStats());
    }

    /**
     * Get all leaves with formatted data
     */
    private function getFormattedLeaves()
    {
        return Leave::with('user')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($leave) => $this->formatLeaveData($leave));
    }

    /**
     * Format individual leave data
     */
    private function formatLeaveData(Leave $leave)
    {
        return [
            'id' => $leave->id,
            'user' => [
                'name' => $leave->user->name ?? 'Unknown User'
            ],
            'reason' => $leave->reason,
            'description' => $leave->description,
            'start_date' => $leave->start_date->format('Y-m-d'),
            'end_date' => $leave->end_date->format('Y-m-d'),
            'status' => $leave->status,
        ];
    }

    /**
     * Get leave request statistics
     */
    private function getLeaveStats()
    {
        return [
            'pending' => Leave::where('status', 'pending')->count(),
            'approved' => Leave::where('status', 'approved')->count(),
            'rejected' => Leave::where('status', 'rejected')->count(),
        ];
    }
}