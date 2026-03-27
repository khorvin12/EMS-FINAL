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
        // Build stable serial numbers based on full unfiltered dataset
        $rankMap = DB::table('attendances')
            ->orderBy('attendances.id', 'asc')
            ->pluck('id')
            ->values()
            ->flip()
            ->map(fn($i) => $i + 1);

        $query = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->select(
                'attendances.id',
                'attendances.employee_id',
                'attendances.date',
                'attendances.check_in',
                'attendances.check_out',
                'attendances.status',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name")
            )
            ->orderBy('attendances.id', 'desc');

        if (request()->filled('search')) {
            $search = request('search');

            $matchingIds = $rankMap->filter(fn($serial) => str_contains((string)$serial, $search))->keys();

            $query->where(function ($q) use ($search, $matchingIds) {
                $q->where(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"), 'like', "%{$search}%")
                  ->orWhere('attendances.status', 'like', "%{$search}%")
                  ->orWhere('attendances.employee_id', 'like', "%{$search}%")
                  ->orWhereIn('attendances.id', $matchingIds);
            });
        }

        if (request()->filled('date_from')) {
            $query->where('attendances.date', '>=', request('date_from'));
        }

        if (request()->filled('date_to')) {
            $query->where('attendances.date', '<=', request('date_to'));
        }

        $attendanceHistory = $query->paginate(6)->withQueryString();

        $attendanceHistory->getCollection()->transform(function ($attendance) use ($rankMap) {
            $hours = 0;
            if ($attendance->check_in && $attendance->check_out) {
                $start = Carbon::parse($attendance->check_in);
                $end = Carbon::parse($attendance->check_out);
                $hours = min(8, round($start->diffInHours($end, true) - 1, 2));
            }
            return [
                'id' => $attendance->id,
                'serial_no' => $rankMap[$attendance->id] ?? null,
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
            'filters' => request()->only(['search', 'date_from', 'date_to']),
        ]);
    }

    public function employees()
    {
        $employees = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->select(
                'attendances.employee_id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'users.first_name'
            )
            ->distinct()
            ->orderBy('users.first_name')
            ->get()
            ->map(function ($emp) {
                $attendance = DB::table('attendances')
                    ->where('employee_id', $emp->employee_id)
                    ->orderBy('date', 'desc')
                    ->get()
                    ->map(fn($a) => (array) $a)
                    ->toArray();

                return [
                    'employee_id' => $emp->employee_id,
                    'employee_name' => $emp->employee_name,
                    'attendance' => $attendance,
                ];
            });

        return Inertia::render('HR/Attendance/View', [
            'employees' => $employees,
        ]);
    }

    public function edit($id)
    {
        $attendance = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->select(
                'attendances.id',
                'attendances.employee_id',
                'attendances.date',
                'attendances.check_in',
                'attendances.check_out',
                'attendances.status',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name")
            )
            ->where('attendances.id', $id)
            ->first();

        if (!$attendance) {
            return redirect()->route('hr.attendance')
                ->with('error', 'Attendance record not found.');
        }

        return Inertia::render('HR/Attendance/Edit', [
            'attendance' => (array) $attendance,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i|after_or_equal:check_in',
            'status' => 'required|in:present,absent,late,on_leave',
        ]);

        DB::table('attendances')->where('id', $id)->update([
            'date' => $request->date,
            'check_in' => $request->check_in ?: null,
            'check_out' => $request->check_out ?: null,
            'status' => $request->status,
            'updated_at' => now(),
        ]);

        return redirect()->route('hr.attendance')
            ->with('success', 'Attendance record updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('attendances')->where('id', $id)->delete();

        return redirect()->back()->with('success', 'Attendance deleted successfully!');
    }
}