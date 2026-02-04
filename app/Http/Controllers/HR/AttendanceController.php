<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        // HR sees ALL employees' attendance
        $attendanceHistory = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.employee_id')
            ->select(
                'attendances.id',
                'attendances.employee_id',
                'attendances.date',
                'attendances.check_in',
                'attendances.check_out',
                'attendances.status',
                'users.name as employee_name'
            )
            ->orderBy('attendances.date', 'desc')
            ->get()
            ->map(function ($attendance) {
                // Calculate hours if both times exist
                $hours = 0;
                if ($attendance->check_in && $attendance->check_out) {
                    $start = Carbon::parse($attendance->check_in);
                    $end = Carbon::parse($attendance->check_out);
                    $hours = round($start->diffInHours($end, true), 2);
                }
                
                return [
                    'id' => $attendance->id,
                    'employee_id' => $attendance->employee_id,
                    'employee_name' => $attendance->employee_name,
                    'date' => $attendance->date,
                    'check_in' => $attendance->check_in,
                    'check_out' => $attendance->check_out,
                    'hours' => $hours,
                    'status' => $attendance->status,
                ];
            });
        
        return Inertia::render('HR/Attendance/Index', [
            'attendanceHistory' => $attendanceHistory,
        ]);
    }

    public function destroy($id)
    {
        DB::table('attendances')->where('id', $id)->delete();
        
        return redirect()->back()->with('success', 'Attendance deleted successfully!');
    }
}