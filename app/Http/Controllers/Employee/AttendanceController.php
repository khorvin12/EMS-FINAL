<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Manila');

        // Get today's attendance
        $todayAttendance = DB::table('attendances')
            ->where('employee_id', $user->employee_id)
            ->whereDate('date', $today)
            ->first();

        // Get attendance history (last 30 days)
        $attendanceHistory = DB::table('attendances')
            ->where('employee_id', $user->employee_id)
            ->select('id', 'employee_id', 'date', 'check_in', 'check_out', 'status')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get()
            ->map(function ($attendance) {
                // Format times for display
                return [
                    'id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'date' => $attendance->date,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'status' => $attendance->status,
                    // Calculate hours if both check_in and check_out exist
                    'hours' => $this->calculateHours($attendance->check_in, $attendance->check_out),
                ];
            });

        return Inertia::render('Employee/Attendance/Index', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->employee_id,
                'name' => $user->name,
            ],
            'todayAttendance' => $todayAttendance,
            'attendanceHistory' => $attendanceHistory,
        ]);
    }

    public function record(Request $request)
    {
        $user = Auth::user();

        // Use Philippine timezone
        $today = Carbon::today('Asia/Manila');
        $currentTime = Carbon::now('Asia/Manila');

        // Find today's attendance record
        $attendance = DB::table('attendances')
            ->where('employee_id', $user->employee_id)
            ->whereDate('date', $today)
            ->first();

        if ($request->type === 'time_in') {
            if ($attendance) {
                return back()->with('error', 'You have already clocked in today.');
            }

            // Determine status (late if after 9:00 AM)
            $status = $currentTime->format('H:i') > '09:00' ? 'late' : 'present';

            DB::table('attendances')->insert([
                'employee_id' => $user->employee_id,
                'date' => $today->format('Y-m-d'),
                'check_in' => $currentTime->format('H:i:s'),
                'status' => $status,
                'created_at' => $currentTime,
                'updated_at' => $currentTime,
            ]);

            return redirect()->back()->with('success', 'Clocked in successfully!');
        }

        if ($request->type === 'time_out') {
            if (!$attendance || $attendance->check_out) {
                return back()->with('error', 'Invalid clock out attempt.');
            }

            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'check_out' => $currentTime->format('H:i:s'),
                    'updated_at' => $currentTime,
                ]);

            return redirect()->back()->with('success', 'Clocked out successfully!');
        }

        return back()->with('error', 'Invalid request.');
    }

    /**
     * Calculate hours worked
     */
    private function calculateHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) {
            return 0;
        }

        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);

        // Subtract 1 hour for lunch break
        $hours = $start->diffInHours($end, true) - 1;

        return round(max(0, $hours), 2);
    }
}