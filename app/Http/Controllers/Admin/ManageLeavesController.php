<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Mail\LeaveStatusMail;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ManageLeavesController extends Controller
{
    public function index()
    {
        $leaves = $this->getFormattedLeaves();
        $stats  = $this->getLeaveStats();

        $user = Auth::user();
        $view = $user->role === 'hr'
            ? 'HR/Leaves/Index'
            : 'Admin/ManageLeaves/Index';

        return Inertia::render($view, [
            'leaves' => $leaves,
            'stats'  => $stats
        ]);
    }

    public function review(Leave $leave)
    {
        $user = Auth::user();
        $view = $user->role === 'hr'
            ? 'HR/Leaves/View'
            : 'Admin/ManageLeaves/LeavesReview';

        return Inertia::render($view, [
            'leave' => $this->formatLeaveData($leave)
        ]);
    }

    public function approve(Leave $leave)
    {
        $leave->load('user');

        $leave->update([
            'status'      => 'approved',
            'approved_by' => Auth::user()->id,
            'approved_at' => now()
        ]);

        Mail::to($leave->user->email)->send(new LeaveStatusMail(
            $leave->user->first_name,
            'approved',
            $leave->start_date->format('M d, Y'),
            $leave->end_date->format('M d, Y'),
            $leave->type ?? 'N/A',
            $leave->admin_comment,
        ));

        $user  = Auth::user();
        $route = $user->role === 'hr'
            ? 'hr.leaves.index'
            : 'admin.manageleaves.index';

        return redirect()->route($route)->with('success', 'Leave approved successfully');
    }

    public function reject(Leave $leave)
    {
        $leave->load('user');

        $leave->update([
            'status'      => 'rejected',
            'rejected_by' => Auth::user()->id,
            'rejected_at' => now()
        ]);

        Mail::to($leave->user->email)->send(new LeaveStatusMail(
            $leave->user->first_name,
            'rejected',
            $leave->start_date->format('M d, Y'),
            $leave->end_date->format('M d, Y'),
            $leave->type ?? 'N/A',
            $leave->admin_comment,
        ));

        $user  = Auth::user();
        $route = $user->role === 'hr'
            ? 'hr.leaves.index'
            : 'admin.manageleaves.index';

        return redirect()->route($route)->with('success', 'Leave rejected successfully');
    }

    public function getStats()
    {
        return response()->json($this->getLeaveStats());
    }

    private function getFormattedLeaves()
    {
        $paginator = Leave::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(6)
            ->withQueryString();

        $paginator->getCollection()->transform(function ($leave) {
            return $this->formatLeaveData($leave);
        });

        return $paginator;
    }

    private function formatLeaveData(Leave $leave)
    {
        return [
            'id'          => $leave->id,
            'user'        => [
                'name'  => $leave->user
                    ? $leave->user->first_name . ' ' . $leave->user->last_name
                    : 'Unknown User',
                'email' => $leave->user?->email ?? 'N/A',
                'id'    => $leave->user?->id,
            ],
            'reason'      => $leave->reason,
            'type'        => $leave->type ?? 'N/A',
            'start_date'  => optional($leave->start_date)?->format('Y-m-d'),
            'end_date'    => optional($leave->end_date)?->format('Y-m-d'),
            'status'      => $leave->status,
            'created_at'  => optional($leave->created_at)?->format('Y-m-d H:i:s'),
            'approved_by' => $leave->approved_by ?? null,
            'rejected_by' => $leave->rejected_by ?? null,
        ];
    }

    private function getLeaveStats()
    {
        $allLeaves = Leave::all();

        return [
            'all'      => $allLeaves->count(),
            'pending'  => $allLeaves->where('status', 'pending')->count(),
            'approved' => $allLeaves->where('status', 'approved')->count(),
            'rejected' => $allLeaves->where('status', 'rejected')->count(),
        ];
    }
}