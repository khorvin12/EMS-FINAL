<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    employee: {
        type: Object,
        required: false,
        default: () => ({
            employee_id: 'Loading...',
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
    
    // If payroll is generated, use the official data
    if (props.payrollGenerated && props.netSalary !== null) {
        return {
            gross: grossSalary,
            deductions: parseFloat(props.salary.deductions || 0),
            net: props.netSalary,
            absenceDeduction: props.deductions?.absence_deduction || 0,
            lateDeduction: props.deductions?.late_deduction || 0,
            undertimeDeduction: props.deductions?.undertime_deduction || 0,
        };
    }
    
    // Otherwise, calculate estimated values
    const totalDeductions = parseFloat(props.salary.deductions || 0);
    const netSalary = grossSalary - totalDeductions;
    
    return {
        gross: grossSalary,
        deductions: totalDeductions,
        net: netSalary,
        absenceDeduction: props.deductions?.absence_deduction || 0,
        lateDeduction: props.deductions?.late_deduction || 0,
        undertimeDeduction: props.deductions?.undertime_deduction || 0,
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
    <Head title="Your Monthly Salary" />

    <!-- Just the content, no layout wrapper -->
    <div class="max-w-7xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8 bg-gray-50">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Your Monthly Salary</h2>
                    <div v-if="payrollGenerated && generatedAt" class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ✓ Payroll Generated
                        </span>
                        <p class="text-xs text-gray-500 mt-1">{{ generatedAt }}</p>
                    </div>
                    <div v-else class="text-right">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            ⏳ Pending Generation
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Estimated values</p>
                    </div>
                </div>

                <!-- Employee Info -->
                <div class="bg-gray-200 rounded-lg p-7 mb-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-1">Employee ID</p>
                      <p class="text-base font-semibold text-gray-900">{{ employee.id }}</p>>
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Gross Salary Card -->
                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-green-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Gross Salary</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.gross) }}</p>
                        <p class="text-sm text-gray-500 opacity-70 font-medium">Base salary + allowances</p>
                    </div>

                    <!-- Total Deductions Card -->
                    <div class="bg-white rounded-xl shadow-sm p-8 border-l-4 border-red-500 hover:shadow-md transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 opacity-70">Total Deductions</p>
                        <p class="text-5xl font-extrabold text-gray-900 mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.deductions) }}</p>
                        <p class="text-sm text-gray-500 opacity-70 font-medium">
                            See breakdown below
                        </p>
                    </div>

                    <!-- Net Salary Card -->
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-8 text-white hover:shadow-xl transition-all hover:-translate-y-1 duration-300">
                        <p class="text-sm font-semibold uppercase tracking-wide mb-3 opacity-90">Net Salary</p>
                        <p class="text-5xl font-extrabold mb-2 leading-none">₱{{ formatCurrency(calculatedSalary.net) }}</p>
                        <p class="text-sm opacity-90 font-medium">Take-home pay this month</p>
                    </div>
                </div>

                <!-- Deductions Breakdown -->
                <div v-if="calculatedSalary.deductions > 0" class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Deductions Breakdown</h3>
                    <div class="space-y-3">
                        <div v-if="calculatedSalary.absenceDeduction > 0" class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">Absence Deduction</p>
                                <p class="text-sm text-gray-500">{{ attendance.absences }} day(s) absent</p>
                            </div>
                            <p class="text-lg font-bold text-red-600">-₱{{ formatCurrency(calculatedSalary.absenceDeduction) }}</p>
                        </div>
                        
                        <div v-if="calculatedSalary.lateDeduction > 0" class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">Late Deduction</p>
                                <p class="text-sm text-gray-500">{{ attendance.late_count }} time(s) late ({{ attendance.total_late_minutes }} minutes)</p>
                            </div>
                            <p class="text-lg font-bold text-red-600">-₱{{ formatCurrency(calculatedSalary.lateDeduction) }}</p>
                        </div>
                        
                        <div v-if="calculatedSalary.undertimeDeduction > 0" class="flex justify-between items-center py-2 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-900">Undertime Deduction</p>
                                <p class="text-sm text-gray-500">{{ attendance.undertime_hours }} hours undertime</p>
                            </div>
                            <p class="text-lg font-bold text-red-600">-₱{{ formatCurrency(calculatedSalary.undertimeDeduction) }}</p>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 bg-red-50 px-4 rounded-lg mt-2">
                            <p class="font-bold text-gray-900 text-lg">Total Deductions</p>
                            <p class="text-xl font-extrabold text-red-600">-₱{{ formatCurrency(calculatedSalary.deductions) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="mt-6 bg-white rounded-xl shadow-sm p-6 border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Attendance Summary</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center p-3 bg-blue-50 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600">{{ attendance.present_days }}</p>
                            <p class="text-xs text-gray-600">Days Present</p>
                        </div>
                        <div class="text-center p-3 bg-red-50 rounded-lg">
                            <p class="text-2xl font-bold text-red-600">{{ attendance.absences }}</p>
                            <p class="text-xs text-gray-600">Days Absent</p>
                        </div>
                        <div class="text-center p-3 bg-yellow-50 rounded-lg">
                            <p class="text-2xl font-bold text-yellow-600">{{ attendance.late_count }}</p>
                            <p class="text-xs text-gray-600">Times Late</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-2xl font-bold text-green-600">{{ attendance.total_hours_worked }}</p>
                            <p class="text-xs text-gray-600">Hours Worked</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>