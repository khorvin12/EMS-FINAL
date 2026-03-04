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

        $todayAttendance = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        $attendanceHistory = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->select('id', 'employee_id', 'date', 'check_in', 'check_out', 'status')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'date' => $attendance->date,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'status' => $attendance->status,
                    'hours' => $this->calculateHours($attendance->check_in, $attendance->check_out),
                ];
            });

        return Inertia::render('Employee/Attendance/Index', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->id,
                'name' => $user->name,
            ],
            'todayAttendance' => $todayAttendance,
            'attendanceHistory' => $attendanceHistory,
        ]);
    }

    public function record(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today('Asia/Manila');
        $currentTime = Carbon::now('Asia/Manila');

        $attendance = DB::table('attendances')
            ->where('employee_id', $user->id)
            ->whereDate('date', $today)
            ->first();

        if ($request->type === 'time_in') {
            if ($attendance) {
                return back()->with('error', 'You have already clocked in today.');
            }
            $status = $currentTime->format('H:i') > '09:00' ? 'late' : 'present';
            DB::table('attendances')->insert([
                'employee_id' => $user->id,
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
            DB::table('attendances')->where('id', $attendance->id)->update([
                'check_out' => $currentTime->format('H:i:s'),
                'updated_at' => $currentTime,
            ]);
            return redirect()->back()->with('success', 'Clocked out successfully!');
        }

        return back()->with('error', 'Invalid request.');
    }

    private function calculateHours($checkIn, $checkOut)
    {
        if (!$checkIn || !$checkOut)
            return 0;
        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);
        return round(max(0, $start->diffInHours($end, true) - 1), 2);
    }
}