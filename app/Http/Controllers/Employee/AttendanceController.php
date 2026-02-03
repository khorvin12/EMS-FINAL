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
        $today = Carbon::today();
        
        // Get today's attendance
        $todayAttendance = DB::table('attendances')
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
        
        // Get attendance history (last 30 days)
        $attendanceHistory = DB::table('attendances')
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
        
        return Inertia::render('Employee/Attendance/Index', [
            'employee' => [
                'id' => $user->id,
                'employee_id' => $user->employee_id ?? $user->id,
                'name' => $user->name,
            ],
            'todayAttendance' => $todayAttendance,
            'attendanceHistory' => $attendanceHistory,
        ]);
    }
    
    public function record(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();
        $currentTime = Carbon::now()->format('H:i:s');
        
        // Find today's attendance record
        $attendance = DB::table('attendances')
            ->where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
        
        if ($request->type === 'time_in') {
            if ($attendance) {
                return back()->with('error', 'You have already clocked in today.');
            }
            
            // Determine status (late if after 9:00 AM)
            $status = Carbon::now()->format('H:i') > '09:00' ? 'late' : 'present';
            
            DB::table('attendances')->insert([
                'user_id' => $user->id,
                'employee_id' => $user->employee_id ?? $user->id,
                'date' => $today,
                'time_in' => $currentTime,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            return redirect()->back()->with('success', 'Clocked in successfully!');
        }
        
        if ($request->type === 'time_out') {
            if (!$attendance || $attendance->time_out) {
                return back()->with('error', 'Invalid clock out attempt.');
            }
            
            $timeIn = Carbon::parse($attendance->time_in);
            $timeOut = Carbon::now();
            $hours = $timeOut->diffInHours($timeIn);
            
            DB::table('attendances')
                ->where('id', $attendance->id)
                ->update([
                    'time_out' => $currentTime,
                    'hours' => $hours,
                    'updated_at' => now(),
                ]);
            
            return redirect()->back()->with('success', 'Clocked out successfully!');
        }
        
        return back()->with('error', 'Invalid request.');
    }
}