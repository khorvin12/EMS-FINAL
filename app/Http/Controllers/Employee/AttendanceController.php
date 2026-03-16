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
        $user  = Auth::user();
        $today = Carbon::today('Asia/Manila');

        $todayAttendance = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $todayAttendanceFormatted = $todayAttendance ? [
            'id'          => $todayAttendance->id,
            'employee_id' => $todayAttendance->employee_id,
            'date'        => $todayAttendance->date,
            'check_in'    => $todayAttendance->check_in,
            'check_out'   => $todayAttendance->check_out,
            'status'      => $todayAttendance->status,
            'hours'       => $this->calculateHours($todayAttendance->check_in, $todayAttendance->check_out),
        ] : null;

        $attendanceHistory = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->select('id', 'employee_id', 'date', 'check_in', 'check_out', 'status', 'hours_worked', 'late_minutes')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'id'           => $attendance->id,
                    'employee_id'  => $attendance->employee_id,
                    'date'         => $attendance->date,
                    'check_in'     => $attendance->check_in,
                    'check_out'    => $attendance->check_out,
                    'status'       => $attendance->status,
                    'hours'        => $attendance->hours_worked ?? $this->calculateHours($attendance->check_in, $attendance->check_out),
                    'late_minutes' => $attendance->late_minutes,
                ];
            });

        return Inertia::render('Employee/Attendance/Index', [
            'employee' => [
                'id'          => $user->id,
                'employee_id' => $user->id,
                'name'        => $user->first_name . ' ' . $user->last_name,
            ],
            'todayAttendance'   => $todayAttendanceFormatted,
            'attendanceHistory' => $attendanceHistory,
        ]);
    }

    public function record(Request $request)
    {
        $user        = Auth::user();
        $today       = Carbon::today('Asia/Manila');
        $currentTime = Carbon::now('Asia/Manila');

        $attendance = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($request->type === 'time_in') {
            if ($attendance && $attendance->check_in !== null) {
                return back()->with('error', 'You have already clocked in today.');
            }

            $cutoff      = Carbon::parse('09:00', 'Asia/Manila');
            $isLate      = $currentTime->greaterThan($cutoff);
            $status      = $isLate ? 'late' : 'present';
            $lateMinutes = $isLate ? (int) $cutoff->diffInMinutes($currentTime) : 0;

            if ($attendance) {
                DB::table('attendances')->where('id', $attendance->id)->update([
                    'check_in'     => $currentTime->format('H:i:s'),
                    'status'       => $status,
                    'late_minutes' => $lateMinutes,
                    'updated_at'   => $currentTime,
                ]);
            } else {
                DB::table('attendances')->insert([
                    'employee_id'  => $user->id,
                    'date'         => $today->format('Y-m-d'),
                    'check_in'     => $currentTime->format('H:i:s'),
                    'status'       => $status,
                    'late_minutes' => $lateMinutes,
                    'created_at'   => $currentTime,
                    'updated_at'   => $currentTime,
                ]);
            }

            return back()->with('success', 'Clocked in successfully!');
        }

        if ($request->type === 'time_out') {
            if (!$attendance || !$attendance->check_in || $attendance->check_out) {
                return back()->with('error', 'Invalid clock out attempt.');
            }

            $hoursWorked = $this->calculateHours($attendance->check_in, $currentTime->format('H:i:s'));

            DB::table('attendances')->where('id', $attendance->id)->update([
                'check_out'    => $currentTime->format('H:i:s'),
                'hours_worked' => $hoursWorked,
                'updated_at'   => $currentTime,
            ]);

            return back()->with('success', 'Clocked out successfully!');
        }

        return back()->with('error', 'Invalid request.');
    }

    private function calculateHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut) return 0;
        $start = Carbon::parse($checkIn);
        $end   = Carbon::parse($checkOut);
        return round(max(0, $start->diffInHours($end, true) - 1), 2);
    }
}