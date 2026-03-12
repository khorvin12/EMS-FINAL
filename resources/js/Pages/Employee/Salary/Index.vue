<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: false,
        default: () => ({
            id: null,
            name: 'Loading...',
            department: 'Loading...',
        })
    },
    salary: {
        type: Object,
        required: false,
        default: () => ({
            full_salary: 0,
            deductions: 0
        })
    },
    attendance: {
        type: Object,
        default: () => ({
            absences: 0,
            late_count: 0,
            total_late_minutes: 0,
            total_hours_worked: 0,
            expected_hours: 176,
            undertime_hours: 0,
            overtime_hours: 0,
            overtime_pay: 0,
            present_days: 0,
            total_working_days: 22,
        })
    },
    deductions: {
        type: Object,
        default: () => null
    },
    netSalary: {
        type: Number,
        default: null
    },
    payrollGenerated: {
        type: Boolean,
        default: false
    },
    generatedAt: {
        type: String,
        default: null
    }
});

const calculatedSalary = computed(() => {
    const grossSalary = parseFloat(props.salary.full_salary || 0);
    const workingDaysPerMonth = 22;
    const dailyRate = grossSalary / workingDaysPerMonth;
    const hourlyRate = dailyRate / 8;
    const overtimeRate = hourlyRate * 1.25;

    if (props.payrollGenerated && props.netSalary !== null) {
        const absenceDeduction = props.deductions?.absence_deduction || 0;
        const lateDeduction = props.deductions?.late_deduction || 0;
        const undertimeDeduction = props.deductions?.undertime_deduction || 0;
        const overtimePay = props.attendance?.overtime_pay || (overtimeRate * (props.attendance?.overtime_hours || 0));
        const totalDeductions = parseFloat(props.salary.deductions || 0);

        return {
            gross: grossSalary,
            deductions: totalDeductions,
            net: props.netSalary,
            absenceDeduction,
            lateDeduction,
            undertimeDeduction,
            overtimePay,
        };
    }

    const totalDeductions = parseFloat(props.salary.deductions || 0);
    const overtimePay = overtimeRate * (props.attendance?.overtime_hours || 0);

    return {
        gross: grossSalary,
        deductions: totalDeductions,
        net: grossSalary - totalDeductions + overtimePay,
        absenceDeduction: props.deductions?.absence_deduction || 0,
        lateDeduction: props.deductions?.late_deduction || 0,
        undertimeDeduction: props.deductions?.undertime_deduction || 0,
        overtimePay,
    };
});

const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};
</script>

<template>
    <div class="flex flex-col px-4 md:px-6">

        <Head title=" | Salary Details" />

        <h2 class="text-2xl md:text-4xl font-bold text-center text-gray-900 mb-4">Your Monthly Salary</h2>

        <div class="flex justify-end items-center mb-6">
            <div v-if="payrollGenerated && generatedAt" class="text-right">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    ✓ Payroll Generated
                </span>
                <p class="text-xs text-gray-500 mt-1">{{ generatedAt }}</p>
            </div>
            <div v-else class="text-right">
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    ⏳ Pending Generation
                </span>
                <p class="text-xs text-gray-500 mt-1">Estimated values</p>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="bg-gray-200 rounded-lg p-4 md:p-7 mb-6 grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8">
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Employee ID</p>
                <p class="text-base font-semibold text-gray-900">{{ 'EMP-' + String(employee.id).padStart(3, '0') }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Employee Name</p>
                <p class="text-base font-semibold text-gray-900">{{ employee.name }}</p>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Department</p>
                <p class="text-base font-semibold text-gray-900">{{ employee.department }}</p>
            </div>
        </div>

        <!-- Salary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
            <div
                class="bg-white rounded-xl shadow-sm p-6 md:p-8 border-l-4 border-green-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Gross Salary</p>
                <p class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{
                    formatCurrency(calculatedSalary.gross) }}</p>
                <p class="text-sm text-gray-500 opacity-70 font-medium">Base salary + allowances</p>
            </div>

            <div
                class="bg-white rounded-xl shadow-sm p-6 md:p-8 border-l-4 border-red-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Total Deductions
                </p>
                <p class="text-2xl md:text-4xl lg:text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{
                    formatCurrency(calculatedSalary.deductions) }}</p>
                <p class="text-sm text-gray-500 opacity-70 font-medium">See breakdown below</p>
            </div>

            <div
                class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 md:p-8 text-white hover:shadow-xl transition-all hover:-translate-y-1 duration-300">
                <p class="text-sm font-semibold uppercase tracking-wide mb-3 opacity-90">Net Salary</p>
                <p class="text-2xl md:text-4xl lg:text-5xl font-extrabold mb-2 leading-none">₱{{
                    formatCurrency(calculatedSalary.net) }}</p>
                <p class="text-sm opacity-90 font-medium">Take-home pay this month</p>
            </div>
        </div>

        <!-- Deductions Breakdown -->
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 border border-gray-200 mb-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Deductions Breakdown</h3>
            <div class="space-y-3">
                <div v-if="calculatedSalary.absenceDeduction > 0"
                    class="flex flex-col md:flex-row md:justify-between md:items-center py-2 border-b border-gray-100 gap-1 md:gap-4">
                    <div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Absence Deduction</p>
                        <p class="text-xs md:text-sm text-gray-500">{{ attendance.absences }} day(s) absent</p>
                    </div>
                    <p class="text-base md:text-lg font-bold text-red-600">-₱{{
                        formatCurrency(calculatedSalary.absenceDeduction) }}</p>
                </div>

                <div v-if="calculatedSalary.lateDeduction > 0"
                    class="flex flex-col md:flex-row md:justify-between md:items-center py-2 border-b border-gray-100 gap-1 md:gap-4">
                    <div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Late Deduction</p>
                        <p class="text-xs md:text-sm text-gray-500">{{ attendance.late_count }} time(s) late ({{
                            attendance.total_late_minutes }} minutes)</p>
                    </div>
                    <p class="text-base md:text-lg font-bold text-red-600">-₱{{
                        formatCurrency(calculatedSalary.lateDeduction) }}</p>
                </div>

                <div v-if="calculatedSalary.undertimeDeduction > 0"
                    class="flex flex-col md:flex-row md:justify-between md:items-center py-2 border-b border-gray-100 gap-1 md:gap-4">
                    <div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Undertime Deduction</p>
                        <p class="text-xs md:text-sm text-gray-500">{{ attendance.undertime_hours }} hours undertime</p>
                    </div>
                    <p class="text-base md:text-lg font-bold text-red-600">-₱{{
                        formatCurrency(calculatedSalary.undertimeDeduction) }}</p>
                </div>

                <div v-if="calculatedSalary.overtimePay > 0"
                    class="flex flex-col md:flex-row md:justify-between md:items-center py-2 border-b border-gray-100 gap-1 md:gap-4">
                    <div>
                        <p class="font-medium text-gray-900 text-sm md:text-base">Overtime Pay</p>
                        <p class="text-xs md:text-sm text-gray-500">{{ attendance.overtime_hours || 0 }} hour(s) (1.25x
                            rate)</p>
                    </div>
                    <p class="text-base md:text-lg font-bold text-green-600">+₱{{
                        formatCurrency(calculatedSalary.overtimePay) }}</p>
                </div>

                <div
                    class="flex flex-col md:flex-row md:justify-between md:items-center py-3 bg-red-50 px-4 rounded-lg mt-2 gap-1">
                    <p class="font-bold text-gray-900 text-base md:text-lg">Total Deductions</p>
                    <p class="text-lg md:text-xl font-extrabold text-red-600">-₱{{
                        formatCurrency(calculatedSalary.deductions) }}</p>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Summary</h3>
            <div class="grid grid-cols-3 md:grid-cols-5 gap-3 md:gap-4">
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-xl md:text-2xl font-bold text-blue-600">{{ attendance.present_days }}</p>
                    <p class="text-xs text-gray-600">Days Present</p>
                </div>
                <div class="text-center p-3 bg-red-50 rounded-lg">
                    <p class="text-xl md:text-2xl font-bold text-red-600">{{ attendance.absences }}</p>
                    <p class="text-xs text-gray-600">Days Absent</p>
                </div>
                <div class="text-center p-3 bg-yellow-50 rounded-lg">
                    <p class="text-xl md:text-2xl font-bold text-yellow-600">{{ attendance.late_count }}</p>
                    <p class="text-xs text-gray-600">Times Late</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <p class="text-xl md:text-2xl font-bold text-green-600">{{ attendance.total_hours_worked }}</p>
                    <p class="text-xs text-gray-600">Hours Worked</p>
                </div>
                <div class="text-center p-3 bg-purple-50 rounded-lg col-span-3 md:col-span-1">
                    <p class="text-xl md:text-2xl font-bold text-purple-600">{{ attendance.overtime_hours || 0 }}</p>
                    <p class="text-xs text-gray-600">Overtime Hours</p>
                </div>
            </div>
        </div>
    </div>
</template>