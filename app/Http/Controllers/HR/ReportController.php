<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function attendancePdf(Request $request)
    {
        $query = DB::table('attendances')
            ->join('users', 'attendances.employee_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->select(
                'attendances.id',
                'attendances.date',
                'attendances.check_in',
                'attendances.check_out',
                'attendances.status',
                'attendances.hours_worked',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'departments.name as department'
            );

        if ($request->date_from) {
            $query->whereDate('attendances.date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('attendances.date', '<=', $request->date_to);
        }

        $employeeName = null;
        if ($request->employee_id) {
            $query->where('attendances.employee_id', $request->employee_id);
            $employee = DB::table('users')->where('id', $request->employee_id)->first();
            $employeeName = $employee ? $employee->first_name . ' ' . $employee->last_name : null;
        }

        if ($request->status) {
            $query->where('attendances.status', $request->status);
        }

        $records = $query->orderBy('attendances.date', 'desc')->get();

        $data = [
            'records'      => $records,
            'totalRecords' => $records->count(),
            'totalPresent' => $records->where('status', 'present')->count(),
            'totalLate'    => $records->where('status', 'late')->count(),
            'totalAbsent'  => $records->where('status', 'absent')->count(),
            'dateFrom'     => $request->date_from ? Carbon::parse($request->date_from)->format('M d, Y') : null,
            'dateTo'       => $request->date_to ? Carbon::parse($request->date_to)->format('M d, Y') : null,
            'employeeName' => $employeeName,
            'statusFilter' => $request->status,
            'generatedBy'  => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'generatedAt'  => Carbon::now('Asia/Manila')->format('M d, Y h:i A'),
        ];

        $pdf = Pdf::loadView('reports.attendance', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('attendance-report-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function payrollPdf(Request $request)
    {
        $month = $request->month
            ? Carbon::parse($request->month)->format('F Y')
            : Carbon::now()->format('F Y');

        $records = DB::table('payrolls')
            ->join('users', 'payrolls.employee_id', '=', 'users.id')
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->where('payrolls.month', $month)
            ->where('users.role', 'employee')
            ->select(
                'users.id',
                DB::raw("CONCAT(users.first_name, ' ', users.last_name) as employee_name"),
                'departments.name as department',
                'payrolls.basic_salary',
                'payrolls.deductions',
                'payrolls.net_salary',
                'payrolls.absences',
                'payrolls.late_count',
                'payrolls.overtime_hours',
                'payrolls.overtime_pay',
                'payrolls.month'
            )
            ->orderBy('users.id')
            ->get();

        $data = [
            'records'         => $records,
            'month'           => $month,
            'totalEmployees'  => $records->count(),
            'totalGross'      => $records->sum('basic_salary'),
            'totalDeductions' => $records->sum('deductions'),
            'totalNet'        => $records->sum('net_salary'),
            'generatedBy'     => Auth::user()->first_name . ' ' . Auth::user()->last_name,
            'generatedAt'     => Carbon::now('Asia/Manila')->format('M d, Y h:i A'),
        ];

        $pdf = Pdf::loadView('reports.payroll', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->download('payroll-report-' . Carbon::now()->format('Y-m') . '.pdf');
    }
}