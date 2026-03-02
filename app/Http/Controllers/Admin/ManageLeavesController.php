<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Inertia\Inertia;

class ManageLeavesController extends Controller
{
    /**
     * Display all leave requests with statistics
     * Works for both Admin and HR roles
     */
    public function index()
    {
        $leaves = $this->getFormattedLeaves();
        $stats = $this->getLeaveStats();
        
        // Determine which view to render based on user role
        $user = auth()->user();
        $view = $user->role === 'hr' 
            ? 'HR/Leaves/Index' 
            : 'Admin/ManageLeaves/Index';

        return Inertia::render($view, [
            'leaves' => $leaves,
            'stats' => $stats
        ]);
    }

    /**
     * Show individual leave for review
     * Works for both Admin and HR roles
     */
    public function review(Leave $leave)
    {
        $user = auth()->user();
        
        // Use different views for HR and Admin
        // HR uses 'View.vue', Admin uses 'LeavesReview'
        $view = $user->role === 'hr' 
            ? 'HR/Leaves/View' 
            : 'Admin/ManageLeaves/LeavesReview';

        return Inertia::render($view, [
            'leave' => $this->formatLeaveData($leave)
        ]);
    }

    /**
     * Approve a leave request
     * Works for both Admin and HR roles
     */
    public function approve(Leave $leave)
    {
        $leave->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);
        
        $user = auth()->user();
        $route = $user->role === 'hr' 
            ? 'hr.leaves.index' 
            : 'admin.manageleaves.index';
        
        return redirect()
            ->route($route)
            ->with('success', 'Leave approved successfully');
    }

    /**
     * Reject a leave request
     * Works for both Admin and HR roles
     */
    public function reject(Leave $leave)
    {
        $leave->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_at' => now()
        ]);
        
        $user = auth()->user();
        $route = $user->role === 'hr' 
            ? 'hr.leaves.index' 
            : 'admin.manageleaves.index';
        
        return redirect()
            ->route($route)
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
     * Both Admin and HR can see ALL leave requests
     */
    private function getFormattedLeaves()
{
    return Leave::with('user')
        ->orderBy('created_at', 'desc')
        ->paginate(6) // Change this to the number of items per page
        ->through(fn($leave) => $this->formatLeaveData($leave));
}

    /**
     * Format individual leave data
     */
    private function formatLeaveData(Leave $leave)
    {
        return [
            'id' => $leave->id,
            'user' => [
                'name' => $leave->user->name ?? 'Unknown User',
                'email' => $leave->user->email ?? 'N/A',
                'id' => $leave->user->id ?? null
            ],
            'reason' => $leave->reason,
            'description' => $leave->description,
            'start_date' => $leave->start_date->format('Y-m-d'),
            'end_date' => $leave->end_date->format('Y-m-d'),
            'status' => $leave->status,
            'created_at' => $leave->created_at->format('Y-m-d H:i:s'),
            'approved_by' => $leave->approved_by ?? null,
            'rejected_by' => $leave->rejected_by ?? null,
        ];
    }

    /**
     * Get leave request statistics
     * Shows counts for all statuses
     */
    private function getLeaveStats()
    {
        $allLeaves = Leave::all();
        
        return [
            'all' => $allLeaves->count(),
            'pending' => $allLeaves->where('status', 'pending')->count(),
            'approved' => $allLeaves->where('status', 'approved')->count(),
            'rejected' => $allLeaves->where('status', 'rejected')->count(),
        ];
    }
}